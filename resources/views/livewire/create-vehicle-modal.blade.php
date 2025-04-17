<div>
    <x-modal name="create-vehicle" wire:model="show">
        <div class="bg-gray-900 rounded-lg overflow-hidden">
            <!-- Fejléc -->
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-white">Jármű regisztrálása</h2>
                </div>
                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Űrlap -->
            <div class="p-6">
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Rendszám -->
                        <div class="relative">
                            <label for="plate_number" class="block text-sm font-medium text-gray-300 mb-2">
                                Rendszám <span class="text-blue-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                    id="plate_number"
                                    wire:model="plate_number"
                                    class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700"
                                    placeholder="ABC-123">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('plate_number') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jármű típus -->
                        <div>
                            <label for="vehicle_type_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Jármű típusa <span class="text-blue-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="vehicle_type_id"
                                        wire:model="vehicle_type_id"
                                        class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700">
                                    <option value="">Válassz típust...</option>
                                    @foreach($vehicleTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('vehicle_type_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jármű azonosító -->
                        <div>
                            <label for="veh_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Jármű azonosító <span class="text-blue-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                    id="veh_id"
                                    wire:model="veh_id"
                                    class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('veh_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Forgalmi érvényessége -->
                        <div>
                            <label for="registration_expiry" class="block text-sm font-medium text-gray-300 mb-2">
                                Forgalmi érvényessége <span class="text-blue-400">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" 
                                    id="registration_expiry"
                                    wire:model="registration_expiry"
                                    class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('registration_expiry') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tulajdonosok -->
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white mb-2">
                                    1. Tulajdonos <span class="text-red-500">*</span>
                                </label>
                                <div wire:ignore x-data="{ 
                                    open: false,
                                    search: '',
                                    users: @js($users),
                                    get filteredUsers() {
                                        if (!this.search) return [];
                                        return this.users.filter(user => 
                                            user.charactername.toLowerCase().includes(this.search.toLowerCase())
                                        );
                                    }
                                }"
                                class="relative">
                                    <input type="text" 
                                        x-model="search"
                                        @focus="open = true"
                                        @click.away="open = false"
                                        class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700"
                                        placeholder="Kezdj el gépelni a kereséshez...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>

                                    <div x-show="open && filteredUsers.length > 0" 
                                        x-cloak
                                        class="absolute z-50 w-full mt-1 bg-gray-800 rounded-lg shadow-lg border border-gray-700 max-h-48 overflow-y-auto">
                                        <template x-for="user in filteredUsers" :key="user.id">
                                            <button type="button"
                                                @click="$wire.selectFirstOwner(user.charactername); search = ''; open = false;"
                                                class="w-full text-left px-4 py-2 text-white hover:bg-gray-700 transition-colors"
                                                x-text="user.charactername">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                @if(isset($selectedOwners[0]))
                                    <div class="mt-2 flex items-center justify-between bg-gray-700 px-3 py-2 rounded-lg">
                                        <span class="text-white">{{ $selectedOwners[0] }}</span>
                                        <button type="button" wire:click="removeFirstOwner" class="text-red-400 hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- 2. Tulajdonos (opcionális) -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white mb-2">
                                    2. Tulajdonos
                                </label>
                                <div wire:ignore x-data="{ 
                                    open: false,
                                    search: '',
                                    users: @js($users),
                                    get filteredUsers() {
                                        if (!this.search) return [];
                                        return this.users.filter(user => 
                                            user.charactername.toLowerCase().includes(this.search.toLowerCase())
                                        );
                                    }
                                }"
                                class="relative">
                                    <input type="text" 
                                        x-model="search"
                                        @focus="open = true"
                                        @click.away="open = false"
                                        class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700"
                                        placeholder="Kezdj el gépelni a kereséshez...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>

                                    <div x-show="open && filteredUsers.length > 0" 
                                        x-cloak
                                        class="absolute z-50 w-full mt-1 bg-gray-800 rounded-lg shadow-lg border border-gray-700 max-h-48 overflow-y-auto">
                                        <template x-for="user in filteredUsers" :key="user.id">
                                            <button type="button"
                                                @click="$wire.selectSecondOwner(user.charactername); search = ''; open = false;"
                                                class="w-full text-left px-4 py-2 text-white hover:bg-gray-700 transition-colors"
                                                x-text="user.charactername">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                @if(isset($selectedOwners[1]))
                                    <div class="mt-2 flex items-center justify-between bg-gray-700 px-3 py-2 rounded-lg">
                                        <span class="text-white">{{ $selectedOwners[1] }}</span>
                                        <button type="button" wire:click="removeSecondOwner" class="text-red-400 hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Alosztály -->
                        <div>
                            <label for="subdivision_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Alosztály <span class="text-blue-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="subdivision_id"
                                        wire:model="subdivision_id"
                                        class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700">
                                    <option value="">Válassz alosztályt...</option>
                                    @foreach($subdivisions as $subdivision)
                                        <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('subdivision_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Rang -->
                        <div>
                            <label for="rank_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Rang <span class="text-blue-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="rank_id"
                                        wire:model="rank_id"
                                        class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 border border-gray-700">
                                    <option value="">Válassz rangot...</option>
                                    @foreach($ranks as $rank)
                                        <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('rank_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Gombok -->
                    <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-700">
                        <button type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 flex items-center">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Mégse
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mentés
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</div>
