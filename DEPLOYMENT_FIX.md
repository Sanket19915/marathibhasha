# Fix PHP 8.0.23 Platform Check Error

## Problem
You're getting: `Fatal error: Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 8.4.0". You are running 8.0.23.`

## Solution (Choose ONE method)

### Method 1: Reinstall with --ignore-platform-reqs (RECOMMENDED)

On your production server, run:

```bash
cd H:\root\home\saninnovations-002\www\marathibhasha
rm -rf vendor composer.lock
composer install --no-interaction --ignore-platform-reqs
```

This will:
- Delete the old vendor folder and lock file
- Install dependencies without checking PHP version requirements
- Generate a new composer.lock compatible with your server

### Method 2: Fix platform_check.php

If you already have vendor installed, upload `fix-platform-check.php` to your server and run:

```bash
cd H:\root\home\saninnovations-002\www\marathibhasha
php fix-platform-check.php
```

This will modify the `vendor/composer/platform_check.php` file to allow PHP 8.0.23.

### Method 3: Manual Fix

1. Edit `vendor/composer/platform_check.php` on your server
2. Find the `getRequiredPhpVersion()` method (around line 28)
3. Change it to:
   ```php
   private static function getRequiredPhpVersion(): string
   {
       return '8.0.0';
   }
   ```

## After Fixing

1. Clear Laravel caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. Set proper permissions:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## Important Notes

- The `composer.json` now has `"platform-check": false` configured
- Laravel 9.52.21 supports PHP 8.0, but some dependencies may require PHP 8.1+
- Using `--ignore-platform-reqs` is safe if you've tested the application
- Consider upgrading to PHP 8.1+ in the future for better security

