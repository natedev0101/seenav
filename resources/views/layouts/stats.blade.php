<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    
    <body class="font-sans antialiased bg-gray-900">
        <div class="min-h-screen">
            <!-- Page Content -->
            <main class="mx-auto my-8 max-w-7xl sm:px-6 lg:px-8 bg-gray-900">
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
