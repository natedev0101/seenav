<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Statisztikáim') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
    </x-slot>

    <div class="py-12 statistics-page">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 bg-gray-800/50 rounded-lg shadow-md p-6">
                <h3 class="text-xl text-gray-300">
                    Üdv, <span class="font-semibold text-blue-400">{{ auth()->user()->charactername }}</span>!
                </h3>
                <p class="mt-2 text-gray-400">Itt találod a személyes statisztikáidat.</p>
            </div>

            <!-- Asztali nézet -->
            <div class="hidden md:block overflow-x-auto">
                <div class="bg-gray-800/50 rounded-lg shadow-md p-1">
                    <table class="min-w-full stats-table">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300 uppercase tracking-wider">
                                    Jelenlegi Rangod
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300 uppercase tracking-wider">
                                    Alosztályaid
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300 uppercase tracking-wider">
                                    Jelentések Statisztikái
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-300">
                                <td class="px-4 py-3">
                                    <div class="stat-section">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                            <span class="stat-value">{{ $user->rank ? $user->rank->name : 'Nincs rang' }}</span>
                                        </div>
                                        @if($user->last_rank_change)
                                            <div class="mt-3 flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="stat-label">Utolsó előléptetés:</span>
                                                <span class="stat-value small">{{ $user->last_rank_change->format('Y.m.d') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="stat-section">
                                        @if($user->subdivisions->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($user->subdivisions as $subdivision)
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        <span class="stat-value">{{ $subdivision->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-2 text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                <span>Nincs alosztály</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="stat-section space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="stat-label">Összes jelentés:</span>
                                            <span class="stat-value">{{ $reportsStats['total'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="stat-label">Elfogadott:</span>
                                            <span class="stat-badge stat-badge-green">{{ $reportsStats['approved'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="stat-label">Elutasított:</span>
                                            <span class="stat-badge stat-badge-red">{{ $reportsStats['rejected'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="stat-label">Függőben:</span>
                                            <span class="stat-badge stat-badge-yellow">{{ $reportsStats['pending'] }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobil nézet -->
            <div class="block md:hidden space-y-4">
                <!-- Rang kártya -->
                <div class="bg-gray-800/50 rounded-lg shadow-md p-4">
                    <div class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-3">
                        Jelenlegi Rangod
                    </div>
                    <div class="stat-section">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            <span class="stat-value">{{ $user->rank ? $user->rank->name : 'Nincs rang' }}</span>
                        </div>
                        @if($user->last_rank_change)
                            <div class="mt-3 flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="stat-label">Utolsó előléptetés:</span>
                                <span class="stat-value small">{{ $user->last_rank_change->format('Y.m.d') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alosztályok kártya -->
                <div class="bg-gray-800/50 rounded-lg shadow-md p-4">
                    <div class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-3">
                        Alosztályaid
                    </div>
                    <div class="stat-section">
                        @if($user->subdivisions->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->subdivisions as $subdivision)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="stat-value">{{ $subdivision->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center space-x-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Nincs alosztály</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Jelentések kártya -->
                <div class="bg-gray-800/50 rounded-lg shadow-md p-4">
                    <div class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-3">
                        Jelentések Statisztikái
                    </div>
                    <div class="stat-section space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="stat-label">Összes jelentés:</span>
                            <span class="stat-value">{{ $reportsStats['total'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="stat-label">Elfogadott:</span>
                            <span class="stat-badge stat-badge-green">{{ $reportsStats['approved'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="stat-label">Elutasított:</span>
                            <span class="stat-badge stat-badge-red">{{ $reportsStats['rejected'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="stat-label">Függőben:</span>
                            <span class="stat-badge stat-badge-yellow">{{ $reportsStats['pending'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
