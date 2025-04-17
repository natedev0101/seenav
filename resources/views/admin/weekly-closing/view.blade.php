<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white">
            Heti zárás részletei ({{ \Carbon\Carbon::parse($closedWeek->start_date)->format('Y.m.d') }} - {{ \Carbon\Carbon::parse($closedWeek->end_date)->format('Y.m.d') }})
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigációs sáv -->
            <div class="bg-gray-800/50 rounded-lg shadow-lg mb-6">
                <div class="px-4 py-3 sm:px-6 flex items-center space-x-4">
                    <a href="{{ route('admin.weekly-closing.index') }}" class="text-gray-300 hover:text-white transition-colors">
                        Vissza a heti zárásokhoz
                    </a>
                </div>
            </div>

            <!-- Fizetések -->
            <div class="bg-gray-800/50 shadow-md rounded-lg p-1 w-full max-w-[1400px] mx-auto">
                <div class="overflow-x-auto w-full">
                    <table class="w-full table-auto min-w-[1200px] text-xs lg:text-sm">
                        <thead class="bg-gray-700/50">
                            <tr>
                                <th class="p-1 text-left font-medium text-gray-400 uppercase tracking-wider">Név</th>
                                <th class="p-1 text-left font-medium text-gray-400 uppercase tracking-wider">Rendfokozat</th>
                                <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <span>Perc</span>
                                    </div>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <span class="bg-yellow-100/10 text-yellow-100 px-1 py-0.5 rounded-md">Óra</span>
                                    </div>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <span class="bg-gray-500/10 text-gray-400 px-1 py-0.5 rounded-md">
                                        Jelentések
                                    </span>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <span class="bg-blue-500/10 text-blue-400 px-1 py-0.5 rounded-md">
                                        MERKUR
                                    </span>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <span class="bg-green-500/10 text-green-400 px-1 py-0.5 rounded-md">
                                        ADÓ
                                    </span>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <span class="bg-yellow-500/10 text-yellow-400 px-1 py-0.5 rounded-md">
                                        KNyF
                                    </span>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <span class="bg-amber-500/10 text-amber-400 px-1 py-0.5 rounded-md">
                                        BEO
                                    </span>
                                </th>
                                <th class="p-1 text-center font-medium uppercase tracking-wider">
                                    <span class="bg-pink-500/10 text-pink-400 px-1 py-0.5 rounded-md">
                                        SZANITÉC
                                    </span>
                                </th>
                                <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <span class="bg-emerald-500/10 text-emerald-400 px-1 py-0.5 rounded-md">Top jelentés</span>
                                    </div>
                                </th>
                                <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <span class="bg-sky-500/10 text-sky-400 px-1 py-0.5 rounded-md">Alap fizetés</span>
                                    </div>
                                </th>
                                <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <span>Fizetés</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800/30">
                            @foreach($salaries as $salary)
                                <tr class="hover:bg-gray-700/30">
                                    <td class="p-1 whitespace-nowrap text-left text-white">
                                        <a href="{{ route('admin.weekly-closing.user-reports', ['weekId' => $closedWeek->id, 'userId' => $salary->user_id]) }}" 
                                           class="hover:text-blue-400 transition-colors">
                                            {{ $salary->charactername }}
                                        </a>
                                        <div class="text-xs text-gray-500">{{ $salary->rank_name }}</div>
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-left text-gray-300">
                                        {{ $salary->rank_name }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-gray-300">
                                        {{ $salary->duty_minutes }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-yellow-100">
                                        {{ number_format($salary->total_hours, 1) }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-gray-300">
                                        {{ $salary->reports_count }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-blue-400">
                                        {{ $salary->merkur_bonus }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-green-400">
                                        {{ $salary->tax_bonus }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-yellow-400">
                                        {{ $salary->knyf_bonus }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-amber-400">
                                        {{ $salary->beo_bonus }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-pink-400">
                                        {{ $salary->medic_bonus }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-emerald-400">
                                        {{ $salary->top_report_bonus }}
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-sky-400">
                                        {{ number_format($salary->base_salary, 0, ',', ' ') }} Ft
                                    </td>
                                    <td class="p-1 whitespace-nowrap text-center text-white font-medium">
                                        {{ number_format($salary->total_salary, 0, ',', ' ') }} Ft
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Duty Times -->
            <div class="bg-gray-800/50 overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Szolgálati idők</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Név</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Kezdés</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Befejezés</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Időtartam</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Szünet</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach($dutyTimes as $dutyTime)
                                    <tr class="hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $dutyTime->charactername }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            {{ \Carbon\Carbon::parse($dutyTime->started_at)->format('Y.m.d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            {{ \Carbon\Carbon::parse($dutyTime->ended_at)->format('Y.m.d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            {{ gmdate('H:i', $dutyTime->total_duration * 60) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            {{ gmdate('H:i', $dutyTime->total_pause_duration * 60) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Reports -->
            <div class="bg-gray-800/50 overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Jelentések</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Név</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Gyanúsított</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Típus</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Büntetés</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Státusz</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Dátum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $report->charactername }}
                                            @if(isset($reportPartners[$report->id]))
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Partnerek: 
                                                    @foreach($reportPartners[$report->id] as $partner)
                                                        {{ $partner->charactername }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $report->suspect_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            {{ $report->type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right">
                                            {{ number_format($report->fine_amount, 0, ',', ' ') }} Ft
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($report->status === 'APPROVED') bg-green-500/10 text-green-400
                                                @elseif($report->status === 'REJECTED') bg-red-500/10 text-red-400
                                                @else bg-yellow-500/10 text-yellow-400
                                                @endif">
                                                {{ $report->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-center">
                                            {{ \Carbon\Carbon::parse($report->report_date)->format('Y.m.d') }}
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
