<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white">
            Heti zárások
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
                <div class="px-4 py-3 sm:px-6 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('reports.index') }}" class="text-gray-300 hover:text-white transition-colors">
                            Vissza a jelentésekhez
                        </a>
                    </div>
                    <form action="{{ route('admin.weekly-closing.close') }}" method="POST" id="closeWeekForm">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col space-y-1">
                                <label for="start_date" class="text-sm text-gray-400">Kezdő dátum (vasárnap)</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $defaultStartDate }}" 
                                    class="bg-gray-700/50 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label for="end_date" class="text-sm text-gray-400">Záró dátum (vasárnap)</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $defaultEndDate }}"
                                    class="bg-gray-700/50 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            </div>
                            <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors flex items-center space-x-2 mt-6">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Időszak lezárása</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-400/20 text-green-400 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-400/20 text-red-400 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-gray-800/50 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700/50">
                            <thead class="bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Időszak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lezárás ideje</th>
                                    <th class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Jelentések és szolgálatok</th>
                                    <th class="px-6 py-4 whitespace-nowrap text-sm text-right">Részletek</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                @foreach($closedWeeks as $week)
                                    <tr class="hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ \Carbon\Carbon::parse($week->start_date)->format('Y.m.d') }} - {{ \Carbon\Carbon::parse($week->end_date)->format('Y.m.d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ \Carbon\Carbon::parse($week->created_at)->format('Y.m.d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $week->reports_count }} jelentés, {{ $week->duty_times_count }} szolgálat
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            <a href="{{ route('admin.weekly-closing.view', $week->id) }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Részletek</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $closedWeeks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.getElementById('closeWeekForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const startDate = new Date(this.querySelector('#start_date').value);
        const endDate = new Date(this.querySelector('#end_date').value);

        // Ellenőrizzük, hogy vasárnapok-e
        if (startDate.getDay() !== 0) {
            alert('A kezdő dátumnak vasárnapnak kell lennie!');
            return;
        }

        if (endDate.getDay() !== 0) {
            alert('A záró dátumnak vasárnapnak kell lennie!');
            return;
        }
        
        const formData = new FormData(this);
        console.log('Form adatok:', {
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            _token: formData.get('_token')
        });

        // Letiltjuk a gombot
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Feldolgozás...</span>
        `;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Válasz státusz:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Válasz:', data);
            
            if (data.error) {
                alert('Hiba történt: ' + data.error);
            } else if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Hiba:', error);
            alert('Hiba történt: ' + error.message);
        })
        .finally(() => {
            // Visszaállítjuk a gombot
            submitButton.disabled = false;
            submitButton.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Időszak lezárása</span>
            `;
        });
    });

    // Dátumválasztók frissítése
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', function() {
            const date = new Date(this.value);
            if (date.getDay() !== 0) {
                alert('Csak vasárnapot lehet kiválasztani!');
                this.value = '';
            }
        });
    });
</script>
@endpush
