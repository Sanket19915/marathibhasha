# MarathiBhasha

A web application for displaying meanings of Marathi words, built with Laravel 12 and Tailwind CSS.

## Features

- **Marathi Language Support**: Full support for Marathi language with Devanagari script
- **Word Dictionary**: Search and display meanings of 267,000+ Marathi words
- **Admin Panel**: Administrative interface for managing content
- **Responsive Design**: Modern UI built with Tailwind CSS
- **Static Pages**: 5-6 static pages (to be implemented)

## Tech Stack

- **Laravel**: 12.x
- **PHP**: 8.2+
- **Tailwind CSS**: v4
- **Vite**: Asset compilation
- **Database**: MySQL/PostgreSQL (to be configured)

## Installation

1. **Clone the repository** (if applicable) or navigate to the project directory:
   ```bash
   cd marathibhashs
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node dependencies**:
   ```bash
   npm install
   ```

4. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**:
   - Update `.env` file with your database credentials
   - Run migrations (once database schema is analyzed):
     ```bash
     php artisan migrate
     ```

6. **Build Assets**:
   ```bash
   npm run build
   # Or for development:
   npm run dev
   ```

7. **Start Development Server**:
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Development

### Running in Development Mode

1. **Start Laravel development server**:
   ```bash
   php artisan serve
   ```

2. **Start Vite dev server** (in a separate terminal):
   ```bash
   npm run dev
   ```

### Project Structure

```
marathibhashs/
├── app/                    # Application code
│   ├── Http/
│   │   └── Controllers/    # Controllers
│   └── Models/             # Eloquent models
├── config/                 # Configuration files
├── database/              # Migrations and seeders
├── resources/
│   ├── css/               # Styles (Tailwind CSS)
│   ├── js/                # JavaScript
│   ├── lang/              # Language files
│   │   └── mr/            # Marathi translations
│   └── views/             # Blade templates
│       ├── layouts/       # Layout templates
│       ├── pages/         # Frontend pages
│       └── admin/         # Admin pages
└── routes/                # Route definitions
```

### Language Configuration

The application is configured to use Marathi (mr) as the default locale. Language files are located in `resources/lang/mr/`.

### Fonts

The application uses the following fonts for Devanagari script:
- Noto Sans Devanagari
- Mukta

These fonts are loaded from Google Fonts.

## Next Steps

1. **Database Setup**:
   - Analyze existing database structure
   - Create migration files
   - Set up database connection

2. **Frontend Development**:
   - Implement word search functionality
   - Create word detail pages
   - Implement static pages

3. **Admin Panel**:
   - Implement admin-specific functionality (details to be shared)

See `DEVELOPMENT_PLAN.md` for detailed development phases and tasks.

## Documentation

- **Development Plan**: See `DEVELOPMENT_PLAN.md` for detailed project roadmap
- **Laravel Documentation**: https://laravel.com/docs
- **Tailwind CSS Documentation**: https://tailwindcss.com/docs

## License

[To be determined]

## Support

For questions or issues, please contact the development team.
