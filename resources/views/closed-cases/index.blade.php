<x-app-layout>
<x-slot name="header">
        <div class="flex flex-col items-start">
            <div class="mb-6">
                <a href="{{ route('cases.index') }}" class="flex items-center text-white hover:text-gray-300 transition navigation-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a1 1 0 01-.707-.293l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L5.414 11H17a1 1 0 110 2H5.414l5.293 5.293A1 1 0 0110 18z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Vissza a jelentésekhez') }}
                </a>
            </div>
            <h2 class="mt-2 font-semibold text-xl text-white leading-tight">
                {{ __('Lezárt hetek áttekintése') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-500 text-white p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500 text-white p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="container mx-auto px-4 py-8">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-white">Lezárt Hetek</h1>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($closedCases->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-400">Nincsenek lezárt hetek.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-900 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Időszak</th>
                                        <th class="px-6 py-3 bg-gray-900 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Jelentések</th>
                                        <th class="px-6 py-3 bg-gray-900 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Elfogadva</th>
                                        <th class="px-6 py-3 bg-gray-900 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Elutasítva</th>
                                        <th class="px-6 py-3 bg-gray-900 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Összes bírság</th>
                                        <th class="px-6 py-3 bg-gray-900 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Lezárva</th>
                                        <th class="px-6 py-3 bg-gray-900 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Lezárta</th>
                                        <th class="px-6 py-3 bg-gray-900 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($closedCases as $closedCase)
                                        <tr class="hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                                {{ $closedCase->week_range }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white text-center">
                                                {{ $closedCase->total_reports }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-400 text-center">
                                                {{ $closedCase->total_approved }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-400 text-center">
                                                {{ $closedCase->total_rejected }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white text-center">
                                                {{ $closedCase->total_fine_amount ? number_format($closedCase->total_fine_amount, 0, ',', ' ') . ' Ft' : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white text-center">
                                                {{ $closedCase->closed_at ? $closedCase->closed_at->format('Y.m.d') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white text-center">
                                                {{ $closedCase->closedByUser ? $closedCase->closedByUser->charactername : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('closed-cases.show', $closedCase->week_range) }}" 
                                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    @if($closedCase->isDeletable())
                                                        <form action="{{ route('closed-cases.destroy', $closedCase->week_range) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Biztosan törölni szeretné ezt a lezárt hetet?')"
                                                                    class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-6">
                        {{ $closedCases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>