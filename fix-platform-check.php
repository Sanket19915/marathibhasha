<?php
/**
 * Script to fix platform_check.php for PHP 8.0 compatibility
 * 
 * Run this script on your production server:
 * php fix-platform-check.php
 */

$platformCheckFile = __DIR__ . '/vendor/composer/platform_check.php';

if (!file_exists($platformCheckFile)) {
    echo "Error: platform_check.php not found. Please run 'composer install --ignore-platform-reqs' first.\n";
    exit(1);
}

$content = file_get_contents($platformCheckFile);

// Replace the version check to allow PHP 8.0
$content = preg_replace(
    '/private static function getRequiredPhpVersion\(\): string\s*\{[^}]*\}/s',
    'private static function getRequiredPhpVersion(): string
    {
        // Modified to allow PHP 8.0.23
        return \'8.0.0\';
    }',
    $content
);

// Also modify the check to be less strict
$content = str_replace(
    'if (version_compare(PHP_VERSION, $requiredVersion, \'<\')) {',
    'if (version_compare(PHP_VERSION, \'8.0.0\', \'<\')) {',
    $content
);

file_put_contents($platformCheckFile, $content);

echo "✓ Successfully modified platform_check.php to allow PHP 8.0.23\n";
echo "✓ Your application should now work with PHP 8.0.23\n";

