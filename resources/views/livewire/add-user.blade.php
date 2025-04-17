<div class="max-w-4xl mx-auto">
    <!-- Információs kártya -->
    <div class="mb-8 bg-gradient-to-br from-blue-500/10 to-purple-500/10 backdrop-blur-xl border border-blue-400/20 rounded-2xl p-6 transform hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-blue-500/20 rounded-xl">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-400 mb-1">Fontos információ</h3>
                <p class="text-blue-300/90">A felhasználónév és jelszó automatikusan generálásra kerül a regisztráció során.</p>
            </div>
        </div>
    </div>

    <!-- Regisztrációs form -->
    <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-gray-700/50">
        <div class="p-8">
            <form wire:submit.prevent="store" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- IC név -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <x-input-label for="charactername" :value="__('IC név')" class="text-lg text-gray-300" />
                        </div>
                        <x-text-input wire:model="charactername" id="charactername" class="block w-full bg-gray-800/50 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" required autofocus />
                        @error('charactername') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Karakter ID -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </div>
                            <x-input-label for="character_id" :value="__('Karakter ID')" class="text-lg text-gray-300" />
                        </div>
                        <div class="flex items-center space-x-3">
                            <x-text-input 
                                wire:model="character_id"
                                id="character_id" 
                                class="w-32 bg-gray-800/50 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-xl" 
                                type="number"
                                x-data
                                x-on:input="document.getElementById('character_url_preview').textContent = 'https://ucp.see-game.com/v4/character/' + $event.target.value"
                            />
                            <div class="flex-1 px-4 py-2 bg-gray-800/80 rounded-xl">
                                <span class="text-gray-400 text-sm" id="character_url_preview">https://ucp.see-game.com/v4/character/</span>
                            </div>
                        </div>
                        @error('character_id') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rang -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <x-input-label for="rank_id" :value="__('Rang')" class="text-lg text-gray-300" />
                        </div>
                        <select wire:model="rank_id" id="rank_id" class="w-full bg-gray-800/50 border-gray-700 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl">
                            <option value="">Válassz rangot...</option>
                            @foreach($ranks as $rank)
                                <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                            @endforeach
                        </select>
                        @error('rank_id') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alosztály -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <x-input-label for="subdivision_id" :value="__('Alosztály')" class="text-lg text-gray-300" />
                        </div>
                        <select wire:model="subdivision_id" id="subdivision_id" class="w-full bg-gray-800/50 border-gray-700 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-xl">
                            <option value="">Válassz alosztályt...</option>
                            @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                            @endforeach
                        </select>
                        @error('subdivision_id') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Játszott perc -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <x-input-label for="played_minutes" :value="__('Játszott perc')" class="text-lg text-gray-300" />
                        </div>
                        <x-text-input wire:model="played_minutes" id="played_minutes" class="block w-full bg-gray-800/50 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="number" required />
                        @error('played_minutes') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Telefonszám -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <x-input-label for="phone_number" :value="__('Telefonszám')" class="text-lg text-gray-300" />
                        </div>
                        <x-text-input wire:model="phone_number" id="phone_number" class="block w-full bg-gray-800/50 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-xl" type="text" required />
                        @error('phone_number') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jelvényszám -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <x-input-label for="badge_number" :value="__('Jelvényszám')" class="text-lg text-gray-300" />
                        </div>
                        <div class="relative">
                            <x-text-input 
                                wire:model="badge_number" 
                                id="badge_number" 
                                class="block w-full bg-gray-800/50 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-xl" 
                                type="text" 
                                required 
                            />
                            <div wire:loading wire:target="badge_number" class="absolute right-3 top-2">
                                <svg class="animate-spin h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('badge_number') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                        
                        <!-- Jelvényszám ellenőrzés eredménye -->
                        @if(session()->has('badge_exists'))
                            <div class="mt-2 text-yellow-400 text-sm flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>{{ session('badge_exists') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Ajánló -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-blue-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <x-input-label for="recommended_by" :value="__('Ajánló')" class="text-lg text-gray-300" />
                        </div>
                        <x-text-input 
                            wire:model="recommended_by" 
                            id="recommended_by" 
                            class="block w-full bg-gray-800/50 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-xl" 
                            type="text" 
                            required 
                            placeholder="Ki ajánlotta be a jelentkezőt?"
                        />
                        @error('recommended_by') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="group relative px-6 py-3 bg-gradient-to-r from-blue-500/10 to-purple-500/10 hover:from-blue-500/20 hover:to-purple-500/20 text-blue-400 rounded-xl transition-all duration-300 hover:scale-[1.02]">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Tag hozzáadása</span>
                        </span>
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 blur transition-opacity"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Értesítések -->
    @if(session()->has('successful-creation'))
        <div class="mt-6 space-y-4">
            <!-- Sikeres regisztráció üzenet -->
            <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-400/20 text-green-400 p-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300" role="alert">
                {{ session('successful-creation') }}
            </div>

            <!-- Generált belépési adatok -->
            @if($showCredentials)
            <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 border border-blue-400/20 p-6 rounded-xl space-y-4">
                <h3 class="text-lg font-semibold text-blue-400 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <span>Belépési adatok</span>
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between bg-gray-800/50 p-3 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-400">Felhasználónév:</span>
                            <span class="text-blue-400 font-mono">{{ $generated_username }}</span>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $generated_username }}')" class="text-blue-400 hover:text-blue-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between bg-gray-800/50 p-3 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-400">Jelszó:</span>
                            <span class="text-blue-400 font-mono">{{ $generated_password }}</span>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $generated_password }}')" class="text-blue-400 hover:text-blue-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mt-6 bg-gradient-to-r from-red-500/10 to-pink-500/10 border border-red-400/20 text-red-400 p-4 rounded-xl transform hover:scale-[1.02] transition-all duration-300" role="alert">
            {{ session('error') }}
        </div>
    @endif
</div>
