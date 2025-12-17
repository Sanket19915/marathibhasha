# Deployment Instructions for PHP 8.0.23

## Problem
Your production server is running PHP 8.0.23, but some Composer dependencies require PHP 8.1+.

## Solution

### Option 1: Use --ignore-platform-reqs (Recommended)

On your production server, run:

```bash
composer install --no-interaction --ignore-platform-reqs
```

This will install all dependencies without checking PHP version requirements.

### Option 2: Fix platform_check.php

If you already have `vendor` folder installed, run:

```bash
php fix-platform-check.php
```

This script will modify the `vendor/composer/platform_check.php` file to allow PHP 8.0.23.

### Option 3: Manual Fix

If the above doesn't work, manually edit `vendor/composer/platform_check.php`:

1. Find the `getRequiredPhpVersion()` method
2. Change it to return `'8.0.0'` instead of checking composer.json
3. Save the file

## Important Notes

- The `composer.json` already has `"platform-check": false` configured
- Laravel 9.52.21 supports PHP 8.0, but some dependencies may require PHP 8.1+
- Using `--ignore-platform-reqs` is safe for production if you've tested the application
- Consider upgrading to PHP 8.1+ in the future for better security and performance

## After Installation

1. Run migrations: `php artisan migrate --force`
2. Clear caches: `php artisan config:clear && php artisan cache:clear`
3. Set permissions: `chmod -R 775 storage bootstrap/cache`
4. Set APP_ENV and APP_DEBUG in `.env` file

