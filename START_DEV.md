# Development Server Instructions

## Starting the Development Servers

### Option 1: Run Both Servers Separately (Recommended)

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```
This will start Laravel on `http://127.0.0.1:8000`

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```
This will start Vite on `http://127.0.0.1:5173` for hot module replacement

### Option 2: Use Concurrently (if installed)

You can run both servers together:
```bash
npx concurrently "php artisan serve" "npm run dev"
```

## Troubleshooting

### Port Already in Use

If port 8000 is already in use:
```bash
php artisan serve --port=8001
```

If port 5173 is already in use, Vite will automatically try the next available port.

### npm run dev fails

If you get "vite: command not found":
```bash
npm install
```

### php artisan serve fails

Make sure you're in the project root directory and Laravel is properly installed:
```bash
composer install
php artisan --version
```

## Accessing the Application

- **Laravel App**: http://127.0.0.1:8000
- **Vite Dev Server**: http://127.0.0.1:5173 (for asset hot-reloading)

The Vite dev server automatically proxies requests to Laravel, so you can access the app through either URL when both servers are running.

