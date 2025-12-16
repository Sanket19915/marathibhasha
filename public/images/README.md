# Images Folder Structure

## Folder Organization

```
public/images/
├── logo/          # Logo images
├── icons/         # Icon images
└── banners/       # Banner images
```

## How to Use Images in Blade Templates

### Method 1: Using asset() helper (Recommended)
```blade
<img src="{{ asset('images/logo/logo.png') }}" alt="Logo">
```

### Method 2: Direct path
```blade
<img src="/images/logo/logo.png" alt="Logo">
```

### Method 3: Using Vite (for optimized images)
```blade
<img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo">
```

## Examples

### In Header Component
```blade
<img src="{{ asset('images/logo/marathi-bhasha.png') }}" alt="Marathi Bhasha Logo" class="w-48 h-48">
```

### In Pages
```blade
<img src="{{ asset('images/banners/welcome-banner.jpg') }}" alt="Welcome Banner">
```

## Best Practices

1. **Organize by type**: Keep logos, icons, and banners in separate folders
2. **Use descriptive names**: `marathi-bhasha-logo.png` instead of `logo1.png`
3. **Optimize images**: Compress images before uploading for better performance
4. **Use appropriate formats**:
   - PNG for logos and icons (with transparency)
   - JPG for photos and banners
   - SVG for scalable graphics

## Image Sizes Recommendations

- **Logo**: 200x200px to 400x400px
- **Icons**: 32x32px to 64x64px
- **Banners**: 1200x400px to 1920x600px




