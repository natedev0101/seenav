<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white">
            {{ $user->charactername }} jelentései ({{ \Carbon\Carbon::parse($closedWeek->start_date)->format('Y.m.d') }} - {{ \Carbon\Carbon::parse($closedWeek->end_date)->format('Y.m.d') }})
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigációs sáv -->
            <div class="bg-gray-800/50 rounded-lg shadow-lg mb-6">
                <div class="px-4 py-3 sm:px-6 flex items-center space-x-4">
                    <a href="{{ route('admin.weekly-closing.view', $closedWeek->id) }}" class="text-gray-300 hover:text-white transition-colors">
                        Vissza a heti zárás részleteihez
                    </a>
                </div>
            </div>

            <!-- Jelentések -->
            <div class="bg-gray-800/50 overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Jelentések ({{ $reports->count() }} db)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Gyanúsított</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Típus</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Bírság</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Státusz</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Dátum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @forelse($reports as $report)
                                    <tr class="hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $report->suspect_name }}
                                            @if(isset($reportPartners[$report->id]))
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Partnerek: 
                                                    @foreach($reportPartners[$report->id] as $partner)
                                                        {{ $partner->charactername }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </div>
                                            @endif
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
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Nincsenek jelentések ebben az időszakban.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
