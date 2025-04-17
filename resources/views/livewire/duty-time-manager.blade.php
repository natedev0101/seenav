<div class="space-y-6" x-data="{ showEndModal: false }" @duty-ended.window="showEndModal = false">
    <!-- Szolgálat vezérlő kártya -->
    <div class="service-card">
        @if(!$activeDuty)
            <button 
                wire:click="startDuty"
                class="service-button service-button-start"
            >
                <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Szolgálatba lépés</span>
            </button>
        @else
            <div class="space-y-4">
                <!-- Aktív szolgálat információk -->
                <div class="text-center">
                    <div 
                        class="text-4xl font-bold text-white mb-2"
                        wire:poll.1000ms="updateTimer"
                    >
                        {{ $elapsedTime }}
                    </div>
                    <div class="text-gray-400">Eltelt idő</div>
                </div>

                <!-- Vezérlő gombok -->
                <div class="flex justify-center space-x-4" x-data @duty-status-updated.window="$wire.$refresh()">
                    @if(!$activeDuty->is_paused)
                        <button 
                            wire:click="pauseDuty"
                            class="bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 hover:text-yellow-300 px-6 py-3 rounded-lg transition-colors flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Szüneteltetés</span>
                        </button>
                    @else
                        <button 
                            wire:click="resumeDuty"
                            class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 px-6 py-3 rounded-lg transition-colors flex items-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Folytatás</span>
                        </button>
                    @endif

                    <button 
                        @click="showEndModal = true"
                        class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 px-6 py-3 rounded-lg transition-colors flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                        <span>Befejezés</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Befejezés modal -->
    <div
        x-show="showEndModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-gray-800 rounded-lg max-w-lg w-full p-6 space-y-4">
                <h3 class="text-lg font-medium text-white">Szolgálat befejezése</h3>
                
                @if($error)
                    <div class="bg-red-500/10 text-red-400 p-3 rounded-lg">
                        {{ $error }}
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-400">
                        Link megadása (kötelező)
                    </label>
                    <input
                        type="url"
                        wire:model="proofLink"
                        class="mt-1 block w-full rounded-lg bg-gray-700 border-transparent focus:border-blue-500 focus:ring-0 text-white"
                        placeholder="https://..."
                    >
                    @error('proofLink')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button
                        @click="showEndModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-300"
                    >
                        Mégse
                    </button>
                    <button
                        wire:click="endDuty"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!$wire.proofLink"
                    >
                        Befejezés
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
