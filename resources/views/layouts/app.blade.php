<!DOCTYPE html>
<html lang="mr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('common.marathi_bhasha')) - {{ config('app.name', 'MarathiBhasha') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Mukta:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Google Sign-In SDK -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col">
        <!-- Sticky Header Component -->
        <x-header />

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 text-sm">
                    &copy; {{ date('Y') }} {{ __('common.marathi_bhasha') }}. {{ __('common.all_rights_reserved') ?? 'सर्व हक्क राखीव' }}
                </p>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>

