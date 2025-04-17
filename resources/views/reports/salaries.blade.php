<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Fizetések') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot>

    <div class="p-1 w-full space-y-4">
        <div class="mb-4">
            <x-back-button />
        </div>

        <style>
            .group-rows {
                transition: all 0.3s ease;
            }
            .group-rows.collapsed {
                display: none;
            }
            .arrow-icon {
                transition: transform 0.3s ease;
            }
            .arrow-icon.collapsed {
                transform: rotate(-90deg);
            }
        </style>

        <script>
            function copyText(text) {
                navigator.clipboard.writeText(text).then(function() {
                    // Sikeresen másoltuk
                    const button = event.target.closest('button');
                    const originalText = button.textContent.trim();
                    button.textContent = 'Másolva!';
                    
                    setTimeout(() => {
                        button.textContent = originalText;
                    }, 2000);
                }).catch(function() {
                    // Hiba történt
                    console.error('Nem sikerült másolni a szöveget');
                });
            }

            function toggleGroup(index) {
                const rows = document.getElementById('group-' + index);
                const arrow = document.getElementById('arrow-' + index);
                
                if (rows.style.display === 'none') {
                    rows.style.display = 'table-row-group';
                    arrow.style.transform = 'rotate(0deg)';
                } else {
                    rows.style.display = 'none';
                    arrow.style.transform = 'rotate(-90deg)';
                }
            }

            // Csoportok megjelenítése kezdetben
            document.addEventListener('DOMContentLoaded', function() {
                const groups = document.querySelectorAll('.group-rows');
                const arrows = document.querySelectorAll('.arrow-icon');
                
                groups.forEach(group => {
                    group.style.display = 'table-row-group';
                });
                
                arrows.forEach(arrow => {
                    arrow.style.transform = 'rotate(0deg)';
                });
            });
        </script>

        <div class="bg-gray-800/50 shadow-md rounded-lg p-1 w-full max-w-[1400px] mx-auto">
            <div class="overflow-x-auto w-full">
                <table class="w-full table-auto min-w-[1200px] text-xs lg:text-sm">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="p-1 text-left font-medium text-gray-400 uppercase tracking-wider">
                                Név
                            </th>
                            <th class="p-1 text-left font-medium text-gray-400 uppercase tracking-wider">
                                Rendfokozat
                            </th>
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
                            <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <span>Megkapta?</span>
                                </div>
                            </th>
                            <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <span>Pay</span>
                                </div>
                            </th>
                            <th class="p-1 text-center font-medium text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <span>Kifizető</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800/30">
                        @foreach($salaryGroups as $group)
                            <tr class="bg-gradient-to-r from-gray-700/50 via-gray-600/50 to-gray-700/50 group cursor-pointer" onclick="toggleGroup('{{ $loop->index }}')">
                                <td colspan="16" class="p-1 text-center relative">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg id="arrow-{{ $loop->index }}" class="w-4 h-4 text-gray-400 arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <span class="font-semibold text-gray-300">{{ $group['name'] }}</span>
                                        <span class="text-gray-500">({{ count($group['salaries']) }} fő)</span>
                                    </div>
                                </td>
                            </tr>
                            <tbody id="group-{{ $loop->index }}" class="group-rows">
                            @foreach($group['salaries'] as $salary)
                            <tr class="hover:bg-gray-700/30">
                                <td class="p-1 whitespace-nowrap text-left text-white">
                                    <a href="{{ route('users.show', $salary->user->id) }}" class="hover:text-blue-400 transition-colors">
                                        {{ $salary->user->charactername }}
                                    </a>
                                </td>
                                <td class="p-1 whitespace-nowrap text-left">
                                    @if($salary->rankColor)
                                    <span class="px-1 py-0.5 rounded-md" style="background-color: {{ $salary->rankColor }}1A; color: {{ $salary->rankColor }}">
                                        {{ $salary->rankName }}
                                    </span>
                                @else
                                    <span class="bg-red-500/10 text-red-400 px-1 py-0.5 rounded-md">NR</span>
                                @endif
                            </td>
                                <td class="p-1 whitespace-nowrap text-center text-white">
                                    {{ round($salary->minutes / 60) }} perc
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-yellow-100/10 text-yellow-100 px-1 py-0.5 rounded-md">
                                        {{ round($salary->minutes / 3600) }} ({{ number_format($salary->minutes / 3600, 1) }})
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center text-white">
                                    {{ $salary->reports_count }}
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-blue-500/10 text-blue-400 px-1 py-0.5 rounded-md">
                                        {{ $salary->merkur_count }}
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-green-500/10 text-green-400 px-1 py-0.5 rounded-md">
                                        {{ $salary->ado_count }}
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-yellow-500/10 text-yellow-400 px-1 py-0.5 rounded-md">
                                        {{ $salary->knyf_count }}
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-amber-500/10 text-amber-400 px-1 py-0.5 rounded-md">
                                        {{ $salary->beo_count }}
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-pink-500/10 text-pink-400 px-1 py-0.5 rounded-md">
                                        {{ $salary->sanitec_count }}
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-emerald-500/10 text-emerald-400 px-1 py-0.5 rounded-md">
                                        {{ $salary->top_report_count }}
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-sky-500/10 text-sky-400 px-1 py-0.5 rounded-md">
                                        {{ number_format($salary->base_salary, 0, '.', ' ') }} $
                                    </span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center text-white">
                                    {{ number_format($salary->total_salary, 0, '.', ' ') }} $
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <span class="bg-red-500/10 text-red-400 px-1 py-0.5 rounded-md">Nem</span>
                                </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    <button onclick="copyText('pay {{ str_replace(' ', '_', $salary->user->charactername) }} {{ $salary->total_salary }}')" class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 px-1 py-0.5 rounded-md transition-colors">Kifizetés</button>
                            </td>
                                <td class="p-1 whitespace-nowrap text-center">
                                    -
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(function() {
                const button = event.target.closest('button');
                const originalText = button.textContent.trim();
                button.textContent = 'Másolva!';
                
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            }).catch(function(err) {
                console.error('Nem sikerült másolni: ', err);
            });
        }
    </script>
</x-app-layout>

@push('scripts')
@endpush
