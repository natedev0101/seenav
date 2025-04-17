<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Felhasználó sikeresen létrehozva') }}
            </h2>
            <a href="{{ route('admin.register_user') }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Új felhasználó létrehozása</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-md rounded-lg">
                <div class="p-6 space-y-6">
                    <!-- Bejelentkezési adatok -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Bejelentkezési adatok</h3>
                            <div class="bg-blue-500/10 p-1 rounded-lg">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Felhasználónév</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->username }}</div>
                            </div>
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Jelszó</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $password }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Személyes adatok -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Személyes adatok</h3>
                            <div class="bg-blue-500/10 p-1 rounded-lg">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">IC név</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->charactername }}</div>
                            </div>
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Karakter ID</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->character_id }}</div>
                            </div>
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Jelvényszám</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->badge_number }}</div>
                            </div>
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Telefonszám</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->phone_number }}</div>
                            </div>
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Játszott perc</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->played_minutes }}</div>
                            </div>
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Beajánlotta</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->recommended_by }}</div>
                            </div>
                            @if($user->rank)
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Rang</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->rank->name }}</div>
                            </div>
                            @endif
                            @if($user->subdivision)
                            <div class="bg-gray-900/50 p-4 rounded-lg">
                                <div class="text-sm text-gray-400">Alosztály</div>
                                <div class="text-lg font-medium text-white mt-1">{{ $user->subdivision->name }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
