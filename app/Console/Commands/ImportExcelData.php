<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Category;
use App\Models\DictionaryEntry;
use App\Http\Controllers\HomeController;

class ImportExcelData extends Command
{
    protected $signature = 'excel:import {file?}';
    protected $description = 'Import Excel data into database';

    public function handle()
    {
        // Find Excel file
        $fileName = $this->argument('file');
        $excelDir = storage_path('excel');
        
        if ($fileName && $fileName !== '*') {
            $filePath = $excelDir . '/' . $fileName;
            // Also try to find file if exact match fails (handles spaces)
            if (!file_exists($filePath)) {
                $files = glob($excelDir . '/*' . $fileName . '*');
                if (!empty($files)) {
                    $filePath = $files[0];
                }
            }
        } else {
            // Get all files and find ones not yet imported
            $files = glob($excelDir . '/*.xlsx');
            if (empty($files)) {
                $files = glob($excelDir . '/*.xls');
            }
            if (empty($files)) {
                $this->error("No Excel files found in: {$excelDir}");
                return 1;
            }
            
            // Get already imported category slugs
            $importedSlugs = Category::pluck('slug')->toArray();
            
            // Find first file that hasn't been imported
            $filePath = null;
            foreach ($files as $file) {
                try {
                    $spreadsheet = IOFactory::load($file);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $categoryName = trim($worksheet->getCell('C2')->getValue() ?? '');
                    $normalizedCategory = trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', ' ', $categoryName));
                    
                    // Try to generate slug for this category
                    $slug = HomeController::generateSlug($normalizedCategory);
                    if (empty($slug)) {
                        // Try fallback map
                        $fallbackMap = [
                            'कृषिशास्त्र' => 'krishishastra',
                            'कृषिशास्त्र ' => 'krishishastra',
                            'विद्युत अभियांत्रिकी' => 'vidyut-abhiyantriki',
                            'विद्युत-अभियांत्रिकी' => 'vidyut-abhiyantriki',
                            'शासन व्यवहार' => 'shasan-vyavahar',
                            'शासन-व्यवहार' => 'shasan-vyavahar',
                            'वैज्ञानिक पारिभाषिक संज्ञा' => 'vaidnyanik-paribhashik-sanjna',
                            'वैज्ञानिक-पारिभाषिक-संज्ञा' => 'vaidnyanik-paribhashik-sanjna',
                            'बैंकिंग शब्दांवली (हिंदी)' => 'banking-shabdavali-hindi',
                            'बैंकिंग-शब्दांवली-हिंदी' => 'banking-shabdavali-hindi',
                            'भूशास्त्र' => 'bhushastra',
                            'भू-शास्त्र' => 'bhushastra',
                            'भौतिकशास्त्र' => 'bhautikshastra',
                            'भौतिकशास्त्र ' => 'bhautikshastra',
                            'मराठी विश्ववकोश परीभाषा कोश' => 'marathi-vishvakosh-paribhasha-kosh',
                            'मराठी-विश्ववकोश-परीभाषा-कोश' => 'marathi-vishvakosh-paribhasha-kosh',
                            'मानसशास्त्र' => 'manasashastra',
                            'मानसशास्त्र ' => 'manasashastra',
                            'यंत्र अभियांत्रिकी' => 'yantra-abhiyantriki',
                            'यंत्र-अभियांत्रिकी' => 'yantra-abhiyantriki',
                            'रसायनशास्त्र' => 'rasayanshastra',
                            'रसायनशास्त्र ' => 'rasayanshastra',
                            'लोकप्रशासन' => 'lokaprashasan',
                            'लोकप्रशासन ' => 'lokaprashasan',
                            'वाणिज्यशास्त्र' => 'vanijyashastra',
                            'विकृतीशास्त्र' => 'vikritishastra',
                            'वित्तीय शब्दांवली' => 'vittiya-shabdavali',
                            'वित्तीय-शब्दांवली' => 'vittiya-shabdavali',
                        ];
                        foreach ($fallbackMap as $key => $fallbackSlug) {
                            if (trim($normalizedCategory) === trim($key)) {
                                $slug = $fallbackSlug;
                                break;
                            }
                        }
                    }
                    
                    // If slug is not in imported list, this file needs to be imported
                    if (!empty($slug) && !in_array($slug, $importedSlugs)) {
                        $filePath = $file;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            if (!$filePath) {
                $filePath = $files[0]; // Fallback to first file
            }
        }

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Reading file: " . basename($filePath));

        try {
            // Use read-only mode to reduce memory usage
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            // Use chunk reading for very large files
            $reader->setReadFilter(new \PhpOffice\PhpSpreadsheet\Reader\DefaultReadFilter());
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $totalRows = $worksheet->getHighestRow();
            
            $this->info("Total rows to process: " . ($totalRows - 1)); // Exclude header
            
            $bar = $this->output->createProgressBar($totalRows - 1);
            $bar->start();

            $imported = 0;
            $skipped = 0;
            $categoryName = null;
            $category = null;

            // Start from row 2 (skip header)
            for ($row = 2; $row <= $totalRows; $row++) {
                $englishWord = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                $marathiMeaning = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
                $categoryNameFromFile = trim($worksheet->getCell('C' . $row)->getValue() ?? '');

                // Skip empty rows
                if (empty($englishWord) && empty($marathiMeaning)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Get or create category (only once per file)
                if (!$category && $categoryNameFromFile) {
                    // Trim and normalize category name
                    $categoryName = trim($categoryNameFromFile);
                    // Normalize category name (replace hyphen with space for matching, but handle special cases)
                    $normalizedName = str_replace('-', ' ', $categoryName);
                    // Special case: भू-शास्त्र should become भूशास्त्र (no space)
                    if (preg_match('/^भू\s*शास्त्र/', $normalizedName)) {
                        $normalizedName = 'भूशास्त्र';
                    }
                    $normalizedName = trim($normalizedName); // Remove any extra spaces
                    $normalizedName = preg_replace('/\s+/', ' ', $normalizedName); // Normalize multiple spaces to single space
                    
                    // Try to get slug with normalized name first
                    $slug = HomeController::generateSlug($normalizedName);
                    
                    // If slug is still null or empty, try with the original name
                    if (empty($slug)) {
                        $slug = HomeController::generateSlug($categoryName);
                    }
                    
                    // If still empty, try variations
                    if (empty($slug)) {
                        // Try common category name variations
                        $variations = [
                            $normalizedName,
                            $categoryName,
                            str_replace(' ', '-', $normalizedName),
                        ];
                        
                        foreach ($variations as $variation) {
                            $slug = HomeController::generateSlug(trim($variation));
                            if (!empty($slug)) {
                                $normalizedName = trim($variation); // Use the variation that worked
                                break;
                            }
                        }
                    }
                    
                    // Last resort: try hardcoded fallback for known categories with special characters
                    if (empty($slug)) {
                        // Normalize whitespace for fallback matching
                        $normalizedForFallback = trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', ' ', $normalizedName));
                        $categoryNameForFallback = trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', ' ', $categoryName));
                        
                        // Handle categories that might have trailing spaces or special formatting
                        $fallbackMap = [
                            'न्यायव्यवहार कोश' => 'nyayavyavahar-kosh',
                            'न्यायव्यवहार-कोश' => 'nyayavyavahar-kosh',
                            'जीवशास्त्र' => 'jivashastra',
                            'जीवशास्त्र ' => 'jivashastra',
                            'धातूशास्त्र' => 'dhatushastra',
                            'धातूशास्त्र ' => 'dhatushastra',
                            'कार्यालयीन शब्दांवली' => 'karyalayin-shabdavali',
                            'कार्यालयीन-शब्दांवली' => 'karyalayin-shabdavali',
                            'कार्यालयीन-शब्दांवली ' => 'karyalayin-shabdavali',
                            'कृषिशास्त्र' => 'krishishastra',
                            'कृषिशास्त्र ' => 'krishishastra',
                            'अर्थशास्त्र' => 'arthashastra',
                            'अर्थशास्त्र ' => 'arthashastra',
                            'औषधशास्त्र' => 'aushadhashastra',
                            'औषधशास्त्र ' => 'aushadhashastra',
                            'विद्युत अभियांत्रिकी' => 'vidyut-abhiyantriki',
                            'विद्युत-अभियांत्रिकी' => 'vidyut-abhiyantriki',
                            'शासन व्यवहार' => 'shasan-vyavahar',
                            'शासन-व्यवहार' => 'shasan-vyavahar',
                            'वैज्ञानिक पारिभाषिक संज्ञा' => 'vaidnyanik-paribhashik-sanjna',
                            'वैज्ञानिक-पारिभाषिक-संज्ञा' => 'vaidnyanik-paribhashik-sanjna',
                            'बैंकिंग शब्दांवली (हिंदी)' => 'banking-shabdavali-hindi',
                            'बैंकिंग-शब्दांवली-हिंदी' => 'banking-shabdavali-hindi',
                            'भूशास्त्र' => 'bhushastra',
                            'भू-शास्त्र' => 'bhushastra',
                            'भौतिकशास्त्र' => 'bhautikshastra',
                            'भौतिकशास्त्र ' => 'bhautikshastra',
                            'मराठी विश्ववकोश परीभाषा कोश' => 'marathi-vishvakosh-paribhasha-kosh',
                            'मराठी-विश्ववकोश-परीभाषा-कोश' => 'marathi-vishvakosh-paribhasha-kosh',
                            'मानसशास्त्र' => 'manasashastra',
                            'मानसशास्त्र ' => 'manasashastra',
                            'यंत्र अभियांत्रिकी' => 'yantra-abhiyantriki',
                            'यंत्र-अभियांत्रिकी' => 'yantra-abhiyantriki',
                            'रसायनशास्त्र' => 'rasayanshastra',
                            'रसायनशास्त्र ' => 'rasayanshastra',
                            'लोकप्रशासन' => 'lokaprashasan',
                            'लोकप्रशासन ' => 'lokaprashasan',
                            'वाणिज्यशास्त्र' => 'vanijyashastra',
                            'विकृतीशास्त्र' => 'vikritishastra',
                            'वित्तीय शब्दांवली' => 'vittiya-shabdavali',
                            'वित्तीय-शब्दांवली' => 'vittiya-shabdavali',
                        ];
                        
                        foreach ($fallbackMap as $key => $fallbackSlug) {
                            $normalizedKey = trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', ' ', $key));
                            
                            if ($normalizedKey === $categoryNameForFallback || $normalizedKey === $normalizedForFallback || 
                                trim($key) === trim($categoryName) || trim($key) === trim($normalizedName)) {
                                $slug = $fallbackSlug;
                                $normalizedName = trim($key);
                                break;
                            }
                        }
                        
                        if (empty($slug)) {
                            $this->error("Could not generate slug for category: {$categoryName}");
                            $this->error("Tried variations: " . implode(', ', [$normalizedName, $categoryName]));
                            $this->error("Please add this category to the slug map in HomeController");
                            return 1;
                        }
                    }
                    
                    $category = Category::firstOrCreate(
                        ['slug' => $slug],
                        ['name_mr' => $normalizedName] // Use normalized name (with space) for consistency
                    );
                    
                    $this->newLine();
                    $this->info("Category: {$normalizedName} (ID: {$category->id}, Slug: {$slug})");
                }

                if (!$category) {
                    $this->newLine();
                    $this->error("No category found in row {$row}");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Create dictionary entry
                if (!empty($englishWord) && !empty($marathiMeaning)) {
                    DictionaryEntry::firstOrCreate(
                        [
                            'category_id' => $category->id,
                            'word_en' => $englishWord,
                        ],
                        [
                            'meaning_mr' => $marathiMeaning,
                        ]
                    );
                    $imported++;
                } else {
                    $skipped++;
                }

                $bar->advance();
                
                // Free memory every 1000 rows
                if ($row % 1000 == 0) {
                    gc_collect_cycles();
                }
            }

            // Clear spreadsheet from memory
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            gc_collect_cycles();

            $bar->finish();
            $this->newLine(2);
            $this->info("Import completed!");
            $this->info("Imported: {$imported} entries");
            $this->info("Skipped: {$skipped} rows");
            
            if ($category) {
                $this->info("Category: {$category->name_mr}");
                $this->info("Total entries in category: " . $category->entries()->count());
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }

}
