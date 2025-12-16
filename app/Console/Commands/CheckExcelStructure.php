<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckExcelStructure extends Command
{
    protected $signature = 'excel:check {file}';
    protected $description = 'Check the structure of an Excel file';

    public function handle()
    {
        $fileName = $this->argument('file');
        $filePath = storage_path('excel/' . $fileName);

        // Try to find the file if exact match fails
        if (!file_exists($filePath)) {
            $excelDir = storage_path('excel');
            $files = glob($excelDir . '/*.xlsx');
            if (empty($files)) {
                $files = glob($excelDir . '/*.xls');
            }
            if (!empty($files)) {
                $filePath = $files[0];
                $this->info("Using file: " . basename($filePath));
            } else {
                $this->error("File not found: {$filePath}");
                $this->error("No Excel files found in: {$excelDir}");
                return 1;
            }
        }

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $this->info("File: {$fileName}");
            $this->info("Total rows: " . $worksheet->getHighestRow());
            $this->info("Total columns: " . $worksheet->getHighestColumn());
            $this->newLine();

            // Show first 5 rows
            $this->info("First 5 rows:");
            for ($row = 1; $row <= min(5, $worksheet->getHighestRow()); $row++) {
                $rowData = [];
                for ($col = 'A'; $col <= $worksheet->getHighestColumn(); $col++) {
                    $cellValue = $worksheet->getCell($col . $row)->getValue();
                    $rowData[] = $cellValue ?? '';
                }
                $this->line("Row {$row}: " . implode(' | ', $rowData));
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error reading file: " . $e->getMessage());
            return 1;
        }
    }
}

