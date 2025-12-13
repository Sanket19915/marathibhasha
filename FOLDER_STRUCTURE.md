# Folder Structure - MarathiBhasha

## Views Organization

The project maintains a clear separation between **Site** (frontend) and **Admin** views:

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Site/Frontend Layout (Light mode only)
│   └── admin.blade.php        # Admin Panel Layout (Light mode only)
├── pages/                     # Site/Frontend Pages
│   └── home.blade.php         # Home page
└── admin/                     # Admin Panel Pages
    └── dashboard.blade.php    # Admin dashboard
```

## Controllers Organization

Controllers are also separated:

```
app/Http/Controllers/
├── HomeController.php         # Site/Frontend Controllers
└── Admin/
    └── AdminController.php    # Admin Panel Controllers
```

## Routes Organization

Routes are organized in `routes/web.php`:

- **Public Routes** (Site/Frontend): `/`
- **Admin Routes**: `/admin/*` (protected with auth middleware)

## Design Decisions

1. **No Dark Mode**: All views use light mode only
2. **Separate Layouts**: 
   - `layouts/app.blade.php` for site pages
   - `layouts/admin.blade.php` for admin pages
3. **Clear Separation**: 
   - Site pages in `pages/` directory
   - Admin pages in `admin/` directory

## Adding New Pages

### For Site Pages:
1. Create view in `resources/views/pages/`
2. Extend `layouts.app`
3. Add route in `routes/web.php`
4. Create controller in `app/Http/Controllers/` (if needed)

### For Admin Pages:
1. Create view in `resources/views/admin/`
2. Extend `layouts.admin`
3. Add route in `routes/web.php` under `admin` prefix with `auth` middleware
4. Create controller in `app/Http/Controllers/Admin/`

## Example

**Site Page:**
```php
// routes/web.php
Route::get('/about', [PageController::class, 'about'])->name('about');

// resources/views/pages/about.blade.php
@extends('layouts.app')
@section('content')
    <!-- Content -->
@endsection
```

**Admin Page:**
```php
// routes/web.php
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/words', [Admin\WordController::class, 'index'])->name('words.index');
});

// resources/views/admin/words/index.blade.php
@extends('layouts.admin')
@section('content')
    <!-- Content -->
@endsection
```


