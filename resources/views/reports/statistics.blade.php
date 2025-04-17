<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $user->charactername }} jelentés statisztikái
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
    </x-slot>
    
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <x-back-button />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Összesített statisztikák -->
            <div class="service-stat-card">
                <div class="service-stat-content">
                    <div class="service-stat-icon-wrapper">
                        <svg class="service-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="service-stat-value">{{ $stats['accepted'] }}</span>
                        <span class="service-stat-label">Elfogadott jelentés</span>
                    </div>
                </div>
            </div>

            <div class="service-stat-card">
                <div class="service-stat-content">
                    <div class="service-stat-icon-wrapper bg-red-500/10 text-red-400">
                        <svg class="service-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="service-stat-value">{{ $stats['rejected'] }}</span>
                        <span class="service-stat-label">Elutasított jelentés</span>
                    </div>
                </div>
            </div>

            <div class="service-stat-card">
                <div class="service-stat-content">
                    <div class="service-stat-icon-wrapper bg-yellow-500/10 text-yellow-400">
                        <svg class="service-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="service-stat-value">{{ $stats['pending'] }}</span>
                        <span class="service-stat-label">Függőben lévő jelentés</span>
                    </div>
                </div>
            </div>

            <div class="service-stat-card">
                <div class="service-stat-content">
                    <div class="service-stat-icon-wrapper bg-purple-500/10 text-purple-400">
                        <svg class="service-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="service-stat-value">{{ $stats['partner'] }}</span>
                        <span class="service-stat-label">Partner jelentés</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Szolgálati idő részletek -->
        <div class="mt-8">
            <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-medium text-white mb-4">Szolgálati idő részletek</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-700/50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Dátum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Kezdés</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Befejezés</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Időtartam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($dutyTimes as $duty)
                                <tr class="hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $duty->started_at ? $duty->started_at->format('Y.m.d') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $duty->started_at ? $duty->started_at->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $duty->ended_at ? $duty->ended_at->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-md">
                                            @if($duty->total_duration)
                                                {{ round($duty->total_duration / 60) }} perc
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jelentések listája -->
        <div class="mt-8">
            <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-medium text-white mb-4">Jelentések</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Azonosító
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Dátum
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Létrehozva
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Típus
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Státusz
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Szerep
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($reports as $report)
                                <tr class="hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        #{{ $report->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $report->report_date ? $report->report_date->format('Y.m.d') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $report->created_at ? $report->created_at->format('Y.m.d H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $report->type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($report->status === 'PENDING')
                                            <span class="bg-yellow-500/10 text-yellow-400 px-3 py-1 rounded-md">
                                                Függőben
                                            </span>
                                        @elseif($report->status === 'APPROVED')
                                            <span class="bg-green-500/10 text-green-400 px-3 py-1 rounded-md">
                                                Elfogadva
                                            </span>
                                        @elseif($report->status === 'REJECTED')
                                            <span class="bg-red-500/10 text-red-400 px-3 py-1 rounded-md">
                                                Elutasítva
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-md">
                                            @if($report->user_id === $user->id)
                                                Beadó
                                            @else
                                                Partner
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>