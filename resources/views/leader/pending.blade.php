<div class="w-full space-y-6" x-data="{ activeSection: 'warnings' }">
    <!-- Fejléc -->
    <div class="bg-gray-800/50 rounded-lg p-4">
        <h2 class="text-xl font-medium text-white">Megerősítésre váró elemek</h2>
        <p class="text-sm text-gray-400 mt-1">Itt találhatók a tisztek által létrehozott, jóváhagyásra váró pontok és figyelmeztetések.</p>
    </div>

    <!-- Navigációs gombok -->
    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
        <button @click="activeSection = 'warnings'" 
                :class="{ 'bg-yellow-500/20 ring-2 ring-yellow-500/50': activeSection === 'warnings', 'bg-yellow-500/10 hover:bg-yellow-500/20': activeSection !== 'warnings' }" 
                class="w-full sm:w-auto text-yellow-400 hover:text-yellow-300 px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>Figyelmeztetések</span>
            <div class="bg-yellow-500/20 text-yellow-400 px-2 py-0.5 rounded-md text-sm">
                <span x-text="warningsCount">0</span>
            </div>
            <div x-show="activeSection === 'warnings'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
                <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
            </div>
        </button>

        <button @click="activeSection = 'plusPoints'" 
                :class="{ 'bg-green-500/20 ring-2 ring-green-500/50': activeSection === 'plusPoints', 'bg-green-500/10 hover:bg-green-500/20': activeSection !== 'plusPoints' }" 
                class="w-full sm:w-auto text-green-400 hover:text-green-300 px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Plusz pontok</span>
            <div class="bg-green-500/20 text-green-400 px-2 py-0.5 rounded-md text-sm">
                <span x-text="plusPointsCount">0</span>
            </div>
            <div x-show="activeSection === 'plusPoints'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
            </div>
        </button>

        <button @click="activeSection = 'minusPoints'" 
                :class="{ 'bg-red-500/20 ring-2 ring-red-500/50': activeSection === 'minusPoints', 'bg-red-500/10 hover:bg-red-500/20': activeSection !== 'minusPoints' }" 
                class="w-full sm:w-auto text-red-400 hover:text-red-300 px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
            <span>Mínusz pontok</span>
            <div class="bg-red-500/20 text-red-400 px-2 py-0.5 rounded-md text-sm">
                <span x-text="minusPointsCount">0</span>
            </div>
            <div x-show="activeSection === 'minusPoints'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
            </div>
        </button>
    </div>

    <!-- Tartalom konténer -->
    <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <!-- Szekció címe -->
        <div class="p-4 bg-gray-700/50 border-b border-gray-700">
            <h2 class="text-xl font-medium text-white flex items-center space-x-2">
                <template x-if="activeSection === 'warnings'">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Megerősítésre váró figyelmeztetések</span>
                    </div>
                </template>
                <template x-if="activeSection === 'plusPoints'">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Megerősítésre váró plusz pontok</span>
                    </div>
                </template>
                <template x-if="activeSection === 'minusPoints'">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                        <span>Megerősítésre váró mínusz pontok</span>
                    </div>
                </template>
            </h2>
        </div>

        <!-- Figyelmeztetések táblázat -->
        <div x-show="activeSection === 'warnings'" x-transition>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm w-[180px]">Név</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 1</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 2</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 3</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 4</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 5</th>
                            <th class="text-right text-gray-300 px-4 py-3 font-medium text-sm w-[100px]">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <template x-for="warning in warnings" :key="warning.id">
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="text-white px-4 py-3" x-text="warning.user"></td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end space-x-2">
                                        <button class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Plusz pontok táblázat -->
        <div x-show="activeSection === 'plusPoints'" x-transition>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm w-[180px]">Név</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 1</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 2</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 3</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 4</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 5</th>
                            <th class="text-right text-gray-300 px-4 py-3 font-medium text-sm w-[100px]">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <template x-for="point in plusPoints" :key="point.id">
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="text-white px-4 py-3" x-text="point.user"></td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end space-x-2">
                                        <button class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mínusz pontok táblázat -->
        <div x-show="activeSection === 'minusPoints'" x-transition>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm w-[180px]">Név</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 1</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 2</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 3</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 4</th>
                            <th class="text-left text-gray-300 px-4 py-3 font-medium text-sm">TESZT 5</th>
                            <th class="text-right text-gray-300 px-4 py-3 font-medium text-sm w-[100px]">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <template x-for="point in minusPoints" :key="point.id">
                            <tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="text-white px-4 py-3" x-text="point.user"></td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="text-gray-300 px-4 py-3">-</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end space-x-2">
                                        <button class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
