<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start">
            <div class="mb-6">
                <a href="{{ route('closed-cases.index') }}" class="flex items-center text-white hover:text-gray-300 transition navigation-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a1 1 0 01-.707-.293l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L5.414 11H17a1 1 0 110 2H5.414l5.293 5.293A1 1 0 0110 18z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Vissza a lezárt hetekhez') }}
                </a>
            </div>
            <h2 class="mt-2 font-semibold text-xl text-white leading-tight">
                {{ __('Jelentések: ') . $closedCase->week_range }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('closed-cases.index') }}" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $closedCase->week_range }}</h1>
                    </div>
                    @if($closedCase->isDeletable())
                        <form action="{{ route('closed-cases.destroy', $closedCase->week_range) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Biztosan törölni szeretné ezt a lezárt hetet?')"
                                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition-colors">
                                Törlés
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Statisztikák -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Összes jelentés</h3>
                        <p class="text-3xl font-bold text-blue-500">{{ $stats['total_reports'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Elfogadva</h3>
                        <p class="text-3xl font-bold text-green-500">{{ $stats['total_approved'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Elutasítva</h3>
                        <p class="text-3xl font-bold text-red-500">{{ $stats['total_rejected'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Összes bírság</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_fine_amount'], 0, ',', ' ') }} Ft</p>
                    </div>
                </div>

                <!-- Legaktívabb felhasználók -->
                @if(!empty($stats['most_active_users']))
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Legaktívabb felhasználók</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($stats['most_active_users'] as $user)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $user->user->charactername }}</p>
                                            <p class="text-sm text-gray-600">{{ $user->report_count }} jelentés</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Jelentések listája -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Jelentések</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Időpont</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Felhasználó</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Járőrtársak</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Típus</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Állampolgár</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indok</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bírság</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Státusz</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reports as $report)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->action_time->format('Y.m.d H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $report->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->partnersList }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->case_type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $report->citizen_name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ Str::limit($report->reason, 50) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->formatted_fine_amount }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $report->status_color }}-100 text-{{ $report->status_color }}-800">
                                                    {{ $report->status_label }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>