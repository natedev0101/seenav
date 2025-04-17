<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SeeMTA NAV - Adatbázis') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- CSS/Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- jQuery és DataTables -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/r-3.0.2/datatables.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/v/dt/dt-2.0.8/r-3.0.2/datatables.js"></script>
        @livewireStyles
        
        <!-- Egyedi stílusok -->
        @stack('styles')
        
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Automatikus kijelentkeztetés script -->
        @auth
        <script src="{{ asset('js/auto-logout.js') }}"></script>
        @endauth

        <!-- Központi értesítés -->
        <div id="notification" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-[9999] scale-0 opacity-0 transition-all duration-300">
            <div class="bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 border border-gray-700">
                <svg id="notification-icon" class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span id="notification-message" class="text-lg whitespace-nowrap"></span>
            </div>
        </div>

        <script>
            function showNotification(message, type = 'success') {
                const notification = document.getElementById('notification');
                const messageElement = document.getElementById('notification-message');
                const icon = document.getElementById('notification-icon');
                
                // Ikon és szín beállítása
                if (type === 'success') {
                    icon.classList.add('text-green-400');
                    icon.classList.remove('text-red-400');
                    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>`;
                } else if (type === 'error') {
                    icon.classList.add('text-red-400');
                    icon.classList.remove('text-green-400');
                    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>`;
                }
                
                messageElement.textContent = message;
                
                // Megjelenítés
                notification.classList.remove('scale-0', 'opacity-0');
                notification.classList.add('scale-100', 'opacity-100');
                
                // 2 másodperc múlva eltűnik
                setTimeout(() => {
                    notification.classList.remove('scale-100', 'opacity-100');
                    notification.classList.add('scale-0', 'opacity-0');
                }, 2000);
            }

            // Livewire eseménykezelő
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('show-notification', (event) => {
                    showNotification(event.message, event.type);
                });
            });
        </script>
    </head>
    
    <body class="font-sans antialiased bg-gray-900 @auth logged-in @endauth">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Cookie Consent -->
            <livewire:cookie-consent />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-gray-800/50 backdrop-blur-sm border-b border-gray-700/50 shadow-lg">
                    <div class="py-4 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            @if (isset($headerIcon))
                                <div class="shrink-0">
                                    {{ $headerIcon }}
                                </div>
                            @endif
                            <div>
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 my-8 bg-gray-900 w-full px-4 sm:px-6 lg:px-8 max-w-[1920px] mx-auto">
                {{ $slot }}
            </main>

            <!-- Modern Footer -->
            <footer class="footer">
                <div class="footer-content">
                    <div class="footer-info">
                        <div class="footer-branding">
                            <span class="footer-name">SeeNAV</span>
                            <span class="text-gray-400">Adatbázis</span>
                            <span class="footer-version">v1.0.0</span>
                        </div>
                        <div class="footer-social">
                            <a href="mailto:natedev@mws.hu" class="footer-contact" title="Email">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>natedev@mws.hu</span>
                            </a>
                            <a href="https://discord.com/users/nate0107" class="footer-social-link" target="_blank" title="Discord: nate0107">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.956-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.955-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.946 2.418-2.157 2.418z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="footer-divider"></div>
                    <div class="footer-links flex items-center space-x-4 text-sm mb-4">
                        <a href="{{ route('policy.cookie') }}" class="text-gray-400 hover:text-gray-300 transition-colors">
                            Cookie Szabályzat
                        </a>
                        <a href="{{ route('policy.terms') }}" class="text-gray-400 hover:text-gray-300 transition-colors">
                            ÁSZF
                        </a>
                        <a href="{{ route('policy.privacy') }}" class="text-gray-400 hover:text-gray-300 transition-colors">
                            Adatvédelem
                        </a>
                    </div>
                    <div class="footer-copyright">
                        <p>&copy; 2025 Developed by Nate</p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Modals Container -->
        <div id="modals-container"></div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
