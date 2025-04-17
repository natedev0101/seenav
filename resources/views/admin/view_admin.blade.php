<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-white leading-tight">
            {{ __('Admin panel') }}
        </h2>
    </x-slot>

    <!-- Dropdown Menu -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="p-6 text-center">
                    <div class="relative inline-block text-left">
                        <button type="button" class="btn" id="dropdown-button" aria-expanded="true" aria-haspopup="true">
                            {{ __('Válassz egy lehetőséget') }}
                        </button>
                        <div class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10" id="dropdown-menu">
                            <div class="py-1">
                                <a href="#heti-statisztika" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Heti statisztika') }}
                                </a>
                                <a href="#elozo-het" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Előző hét (lezárt)') }}
                                </a>
                                <a href="#inaktivitasok" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Inaktivitások') }}
                                </a>
                                <a href="#regisztralt-felhasznalok" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Regisztrált felhasználók') }}
                                </a>
                                <a href="#admin-logok" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Admin logok') }}
                                </a>
                                <a href="{{ route('ranks.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Rangok kezelése') }}
                                </a>
                                <a href="{{ route('subdivisions.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Alosztályok kezelése') }}
                                </a>
                                <a href="{{ route('announcements.create') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-600">
                                    {{ __('Közlemények kezelése') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownButton = document.getElementById('dropdown-button');
            const dropdownMenu = document.getElementById('dropdown-menu');

            dropdownButton.addEventListener('click', () => {
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>