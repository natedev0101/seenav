<x-app-layout>
    <div x-data="filters">
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-white">
                Jelentések
            </h2>
        </x-slot>
        <x-slot name="headerIcon">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </x-slot>

        <!-- Navigációs sáv -->
        <div class="border-b border-gray-700">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-4 flex items-center justify-between">
                </div>
            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <!-- Szűrő gomb -->
                        <button @click="filterOpen = !filterOpen" 
                                class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Szűrő</span>
                            <span x-show="filterOpen" class="text-sm">(aktív)</span>
                        </button>

                        <!-- Gombok -->
                        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-4 justify-center">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.weekly-closing.index') }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Heti zárások</span>
                                </a>
                            @endif
                            <a href="{{ route('reports.create') }}" 
                               class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Új jelentés</span>
                            </a>
                            <a href="{{ route('reports.salaries') }}" 
                               class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Fizetések</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Szűrő panel -->
                <div x-cloak x-show="filterOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="border-b border-gray-800 bg-gray-900/50">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            <!-- Jelentés dátuma -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Jelentés dátuma</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Ettől:</span>
                                        <input type="date" 
                                               x-model="filters.report_date_from"
                                               class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Eddig:</span>
                                        <input type="date" 
                                               x-model="filters.report_date_to"
                                               class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs">
                                    </div>
                                </div>
                            </div>

                            <!-- Leadás dátuma -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Leadás dátuma</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Ettől:</span>
                                        <input type="date" 
                                               x-model="filters.created_at_from"
                                               class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Eddig:</span>
                                        <input type="date" 
                                               x-model="filters.created_at_to"
                                               class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs">
                                    </div>
                                </div>
                            </div>

                            <!-- Beadta -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Beadta</label>
                                <input type="text" 
                                       x-model="filters.user"
                                       class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs"
                                       placeholder="Karakter neve">
                            </div>

                            <!-- Járőrtárs -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Járőrtárs</label>
                                <input type="text" 
                                       x-model="filters.partner"
                                       class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs"
                                       placeholder="Karakter neve">
                            </div>

                            <!-- Elkövető -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Elkövető</label>
                                <input type="text" 
                                       x-model="filters.suspect"
                                       class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs"
                                       placeholder="Elkövető neve">
                            </div>

                            <!-- Típus -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Típus</label>
                                <select x-model="filters.type"
                                        class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs">
                                    <option value="">Összes</option>
                                    <option value="ELŐÁLLÍTÁS">Előállítás</option>
                                    <option value="IGAZOLTATÁS">Igazoltatás</option>
                                </select>
                            </div>

                            <!-- Összeg -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Összeg</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Min:</span>
                                        <input type="number" 
                                               x-model="filters.amount_min"
                                               class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs"
                                               placeholder="0">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-0.5">Max:</span>
                                        <input type="number" 
                                               x-model="filters.amount_max"
                                               class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs"
                                               placeholder="300000">
                                    </div>
                                </div>
                            </div>

                            <!-- Státusz -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Státusz</label>
                                <select x-model="filters.status"
                                        class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs">
                                    <option value="">Összes</option>
                                    <option value="PENDING">Függőben</option>
                                    <option value="APPROVED">Elfogadva</option>
                                    <option value="REJECTED">Elutasítva</option>
                                </select>
                            </div>

                            <!-- Kezelő -->
                            <div class="bg-gray-800 p-3 rounded-lg space-y-2">
                                <label class="block text-xs font-medium text-gray-400">Kezelő</label>
                                <input type="text" 
                                       x-model="filters.handler"
                                       class="w-full h-8 bg-gray-700 border-0 rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white text-xs"
                                       placeholder="Kezelő neve">
                            </div>
                        </div>

                        <!-- Szűrő gombok -->
                        <div class="flex items-center justify-end space-x-2 mt-4">
                            <!-- Reset gomb -->
                            <button @click="resetFilters()"
                                    class="bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-gray-200 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Alaphelyzet</span>
                            </button>
                            
                            <!-- Szűrés gomb -->
                            <button @click="applyFilters()"
                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span>Szűrés</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-400/10 text-green-400 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-400/10 text-red-400 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Jelentések Statisztikák -->
                <div class="bg-gray-800/50 rounded-lg p-2 mb-6 overflow-x-auto">
                    <div class="flex items-center space-x-4 whitespace-nowrap min-w-max">
                        <span class="text-gray-400 text-sm">Összes: <span class="bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-md">{{ $stats['total'] }}</span></span>
                        <span class="text-gray-400 text-sm">Elfogadott: <span class="bg-green-500/10 text-green-400 px-2 py-0.5 rounded-md">{{ $stats['approved'] }}</span></span>
                        <span class="text-gray-400 text-sm">Függőben: <span class="bg-yellow-500/10 text-yellow-400 px-2 py-0.5 rounded-md">{{ $stats['pending'] }}</span></span>
                        <span class="text-gray-400 text-sm">Elutasított: <span class="bg-red-500/10 text-red-400 px-2 py-0.5 rounded-md">{{ $stats['rejected'] }}</span></span>
                        <span class="text-gray-400 text-sm">Bírság: <span class="bg-purple-500/10 text-purple-400 px-2 py-0.5 rounded-md">${{ number_format($stats['total_fine']) }}</span></span>
                        <span class="text-gray-400 text-sm">Top: <span class="bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-md truncate max-w-[150px] inline-block align-bottom">{{ $stats['top_report']->user->charactername ?? '-' }}</span></span>
                    </div>
                </div>
                <!-- Táblázat -->
                <div class="bg-gray-800/50 shadow-md rounded-lg p-2">
                    <div class="block sm:hidden">
                        <!-- Mobil nézet -->
                        @forelse($reports as $report)
                            <div class="bg-gray-800/30 p-3 mb-2 rounded-lg">
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <div class="text-xs text-gray-400">Jelentés dátuma</div>
                                        <div class="text-sm text-white">{{ $report->report_date ? $report->report_date->format('Y.m.d') : '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">Leadás dátuma</div>
                                        <div class="text-sm text-white">{{ $report->created_at->format('Y.m.d H:i') }}</div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-xs text-gray-400">Beadta</div>
                                    <div class="text-sm text-white">
                                        <a href="{{ route('reports.statistics', ['user' => $report->user->id]) }}" class="hover:text-blue-400 transition-colors">
                                            {{ $report->user->charactername }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-xs text-gray-400">Járőrtárs</div>
                                    <div class="text-sm text-white">
                                        @if($report->partners->isNotEmpty())
                                            <div class="flex flex-col space-y-1">
                                                @foreach($report->partners as $partner)
                                                    <a href="{{ route('reports.statistics', ['user' => $partner->id]) }}" class="hover:text-blue-400 transition-colors">
                                                        {{ $partner->charactername }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-xs text-gray-400">Elkövető</div>
                                    <div class="text-sm text-white">{{ $report->suspect_name }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-xs text-gray-400">Típus</div>
                                    <div class="text-sm text-white">{{ $report->type }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-xs text-gray-400">Státusz</div>
                                    <div class="text-sm">
                                        @if($report->status === 'PENDING')
                                            <span class="bg-yellow-500/10 text-yellow-400 px-2 py-0.5 rounded-md text-xs">Függőben</span>
                                        @elseif($report->status === 'APPROVED')
                                            <span class="bg-green-500/10 text-green-400 px-2 py-0.5 rounded-md text-xs">Elfogadva</span>
                                        @else
                                            <span class="bg-red-500/10 text-red-400 px-2 py-0.5 rounded-md text-xs">Elutasítva</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('reports.show', $report) }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($report->status === 'PENDING' && (auth()->user()->isAdmin || auth()->user()->id === $report->user_id))
                                        <form action="/jelentesek/{{ $report->id }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd ezt a jelentést?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->isAdmin && $report->status === 'PENDING')
                                        <button onclick="approveReport({{ $report->id }})" class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button onclick="rejectReport({{ $report->id }})" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-400 py-4">Nincsenek jelentések</div>
                        @endforelse
                    </div>
                    <div class="hidden sm:block overflow-x-auto w-full">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-700/50">
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Jelentés dátuma</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Leadás dátuma</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Beadta</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Járőrtársak</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Elkövető</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Típus</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Összeg</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Státusz</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Kezelő</th>
                                    <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Műveletek</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800/30">
                                @forelse($reports as $report)
                                    <tr class="hover:bg-gray-700/50">
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">{{ $report->report_date ? $report->report_date->format('Y.m.d') : '-' }}</td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">{{ $report->created_at->format('Y.m.d H:i') }}</td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">
                                            <a href="{{ route('reports.statistics', ['user' => $report->user->id]) }}" class="hover:text-blue-400 transition-colors">
                                                {{ $report->user->charactername }}
                                            </a>
                                        </td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">
                                            @if($report->partners->isNotEmpty())
                                                <div class="flex flex-col space-y-1">
                                                    @foreach($report->partners as $partner)
                                                        <a href="{{ route('reports.statistics', ['user' => $partner->id]) }}" class="hover:text-blue-400 transition-colors">
                                                            {{ $partner->charactername }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">{{ $report->suspect_name }}</td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">{{ $report->type }}</td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-yellow-400">${{ number_format($report->fine_amount) }}</td>
                                        <td class="px-3 py-2 text-sm">
                                            @if($report->status === 'PENDING')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-yellow-400/10 text-yellow-500">
                                                    Függőben
                                                </span>
                                            @elseif($report->status === 'APPROVED')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-green-400/10 text-green-500">
                                                    Elfogadva
                                                </span>
                                            @else
                                                <span class="group relative inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-red-400/10 text-red-500">
                                                    <span>Elutasítva</span>
                                                    @if($report->rejection_reason)
                                                        <div class="absolute left-0 bottom-full mb-2 hidden group-hover:block w-48 p-2 bg-gray-900 text-red-400 text-xs rounded-lg shadow-lg">
                                                            {{ $report->rejection_reason }}
                                                            <div class="absolute left-4 bottom-[-8px] w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                        </div>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-2 py-2 text-xs lg:text-sm text-white">{{ $report->handler->charactername ?? '-' }}</td>
                                        <td class="px-2 py-2 text-xs lg:text-sm flex items-center gap-1">
                                            @if($report->status === 'PENDING' && (auth()->user()->isAdmin || auth()->user()->id === $report->user_id))
                                                <!-- Törlés gomb -->
                                                <form action="/jelentesek/{{ $report->id }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd ezt a jelentést?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 hover:text-orange-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->isAdmin && $report->status === 'PENDING')
                                                <!-- Elfogadás gomb -->
                                                <button onclick="approveReport({{ $report->id }})" class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>

                                                <!-- Elutasítás gomb -->
                                                <button onclick="rejectReport({{ $report->id }})" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-2 py-4 text-center text-gray-400">Nincsenek jelentések</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Lapozó -->
                    @if($reports->hasPages())
                        <div class="px-2 py-4">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('filters', () => {
                        // URL paraméterek beolvasása
                        const searchParams = new URLSearchParams(window.location.search);
                        const initialFilters = {
                            report_date_from: searchParams.get('report_date_from') || '',
                            report_date_to: searchParams.get('report_date_to') || '',
                            created_at_from: searchParams.get('created_at_from') || '',
                            created_at_to: searchParams.get('created_at_to') || '',
                            user: searchParams.get('user') || '',
                            partner: searchParams.get('partner') || '',
                            suspect: searchParams.get('suspect') || '',
                            type: searchParams.get('type') || '',
                            amount_min: searchParams.get('amount_min') || '',
                            amount_max: searchParams.get('amount_max') || '',
                            status: searchParams.get('status') || '',
                            handler: searchParams.get('handler') || ''
                        };

                        return {
                            filterOpen: Object.values(initialFilters).some(value => value !== ''),
                            filters: initialFilters,
                            applyFilters() {
                                // URL paraméterek frissítése
                                const searchParams = new URLSearchParams();
                                Object.entries(this.filters).forEach(([key, value]) => {
                                    if (value) {
                                        searchParams.append(key, value);
                                    }
                                });

                                // Oldal frissítése az új paraméterekkel
                                window.location.search = searchParams.toString();
                            },
                            resetFilters() {
                                Object.keys(this.filters).forEach(key => this.filters[key] = '');
                                this.applyFilters();
                            }
                        };
                    });
                });

                // Jelentés törlése
                function deleteReport(reportId) {
                    if (confirm('Biztosan törölni szeretnéd ezt a jelentést?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/jelentesek/${reportId}`;
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        
                        form.appendChild(csrfInput);
                        form.appendChild(methodInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }

                // Jelentés elfogadása
                function approveReport(reportId) {
                    fetch(`/jelentesek/${reportId}/elfogadas`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.error || 'Hiba történt az elfogadás során!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hiba történt az elfogadás során!');
                    });
                }

                // Jelentés elutasítása
                function rejectReport(reportId) {
                    const reason = prompt('Add meg az elutasítás indokát (maximum 50 karakter):');
                    if (reason === null) return; // Ha a felhasználó a Cancel-re kattint
                    if (!reason.trim()) {
                        alert('Az indoklás megadása kötelező!');
                        return;
                    }
                    if (reason.length > 50) {
                        alert('Az indoklás nem lehet hosszabb 50 karakternél!');
                        return;
                    }

                    fetch(`/jelentesek/${reportId}/elutasitas`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ reason: reason })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.error || 'Hiba történt az elutasítás során!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hiba történt az elutasítás során!');
                    });
                }
            </script>
        </div>
    </div>
    
</x-app-layout>
