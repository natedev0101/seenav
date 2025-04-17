<x-app-layout>
    <div class="w-full !max-w-none -mx-6 lg:-mx-8">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8 mx-6 lg:mx-8">
            <h1 class="text-2xl font-bold text-white mb-6">Heti Statisztika</h1>
            
            <!-- Összesített adatok -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-gray-400 text-sm">Összes jelentés</h3>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_reports'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-gray-400 text-sm">Elfogadott jelentések</h3>
                    <p class="text-2xl font-bold text-green-400">{{ $stats['total_approved'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-gray-400 text-sm">Elutasított jelentések</h3>
                    <p class="text-2xl font-bold text-red-400">{{ $stats['total_rejected'] ?? 0 }}</p>
                </div>
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-gray-400 text-sm">Összes bírság</h3>
                    <p class="text-2xl font-bold text-yellow-400">${{ number_format($stats['total_fine_amount'] ?? 0) }}</p>
                </div>
            </div>

            <!-- Legaktívabb felhasználók -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Legaktívabb Felhasználók</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($stats['most_active_users'] ?? [] as $user)
                    <div class="bg-gray-800 rounded-lg p-4 flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">{{ $user->user->charactername }}</p>
                            <p class="text-gray-400 text-sm">{{ $user->report_count }} jelentés</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Hét lezárása gomb -->
            @if(auth()->user()->isAdmin)
            <div class="flex justify-end mt-8">
                <button onclick="confirmCloseWeek()" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors">
                    Hét Lezárása
                </button>
            </div>

            <form id="closeWeekForm" action="{{ route('leader.close-week') }}" method="POST" class="hidden">
                @csrf
            </form>

            <script>
                function confirmCloseWeek() {
                    if (confirm('Biztosan le szeretnéd zárni a hetet? Ez a művelet nem vonható vissza!')) {
                        document.getElementById('closeWeekForm').submit();
                    }
                }
            </script>
            @endif
        </div>

        <!-- Fizetések táblázat -->
        <div class="bg-gray-800/50 shadow-md rounded-lg p-2 mx-6 lg:mx-8">
            <div class="overflow-x-auto w-full">
                <table class="w-full table-auto">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Név</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Rendfokozat</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Perc</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Óra</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Jelentés</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">MERKÚR</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">ADÓ</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">KNYF</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">BEO</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">SZANITÉC</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">TOP JELENTÉS</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">ALAP FIZETÉS</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Fizetés</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Megkapta?</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Pay</th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">Kifizető</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800/30">
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">Teszt Elek</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">Őrmester</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">180</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">3</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">15</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">5</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">3</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">2</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">4</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">1</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">2</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-yellow-400">50000</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-yellow-400">65000</td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-red-400">Nem</td>
                            <td class="px-2 py-2">
                                <button class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                    Kifizet
                                </button>
                            </td>
                            <td class="px-2 py-2 text-xs lg:text-sm text-white">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
