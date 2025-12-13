# MarathiBhasha

A web application for displaying meanings of Marathi words, built with Laravel 12 and Tailwind CSS.

## Features

- **Marathi Language Support**: Full support for Marathi language with Devanagari script
- **Word Dictionary**: Search and display meanings of 267,000+ Marathi words
- **Admin Panel**: Administrative interface for managing content
- **Responsive Design**: Modern UI built with Tailwind CSS
- **Static Pages**: Multiple static pages including Objectives, Appeal, Science, etc.
- **Google Sign-In**: Integrated Google One Tap and Sign-In functionality
- **Category-based Glossary**: 35+ categories of specialized glossaries
- **Global Search**: Search words across all categories

## Tech Stack

- **Laravel**: 12.x
- **PHP**: 8.2+
- **Tailwind CSS**: v4
- **Vite**: Asset compilation
- **Database**: MySQL/PostgreSQL (to be configured)
- **Laravel Socialite**: For Google OAuth integration

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Sanket19915/marathibhasha.git
   cd marathibhasha
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
   - Run migrations:
     ```bash
     php artisan migrate
     ```

6. **Configure Google Sign-In** (Optional):
   - Add Google OAuth credentials to `.env`:
     ```env
     GOOGLE_CLIENT_ID=your_client_id
     GOOGLE_CLIENT_SECRET=your_client_secret
     GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
     ```
   - See `GOOGLE_SIGNIN_SETUP.md` for detailed setup instructions

7. **Build Assets**:
   ```bash
   npm run build
   # Or for development:
   npm run dev
   ```

8. **Start Development Server**:
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
marathibhasha/
├── app/                    # Application code
│   ├── Http/
│   │   └── Controllers/    # Controllers
│   ├── Mail/               # Mail classes
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
│       ├── components/    # Reusable components
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

## Features Implemented

- ✅ Homepage with category listing
- ✅ Category pages with word listings
- ✅ Global word search functionality
- ✅ Static pages (Objectives, Appeal, Science, etc.)
- ✅ Google Sign-In integration
- ✅ Word suggestion form
- ✅ Responsive design
- ✅ Marathi language support throughout

## Documentation

- **Development Plan**: See `DEVELOPMENT_PLAN.md` for detailed project roadmap
- **Google Sign-In Setup**: See `GOOGLE_SIGNIN_SETUP.md` for Google OAuth configuration
- **Folder Structure**: See `FOLDER_STRUCTURE.md` for project organization
- **Laravel Documentation**: https://laravel.com/docs
- **Tailwind CSS Documentation**: https://tailwindcss.com/docs

## License

[To be determined]

## Support

For questions or issues, please contact the development team.
