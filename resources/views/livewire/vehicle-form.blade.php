<div class="p-6">
    <h2 class="text-lg font-medium text-white mb-6">
        {{ $vehicle ? 'Jármű szerkesztése' : 'Új jármű hozzáadása' }}
    </h2>

    <form wire:submit="save" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Rendszám -->
            <div>
                <label for="plate_number" class="block text-sm font-medium text-gray-400">Rendszám</label>
                <input type="text" 
                       id="plate_number" 
                       wire:model="plate_number" 
                       class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('plate_number') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Típus -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-400">Típus</label>
                <input type="text" 
                       id="type" 
                       wire:model="type" 
                       class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('type') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- VehID -->
            <div>
                <label for="veh_id" class="block text-sm font-medium text-gray-400">VehID</label>
                <input type="text" 
                       id="veh_id" 
                       wire:model="veh_id" 
                       class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('veh_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Forgalmi érvényesség -->
            <div>
                <label for="registration_expiry" class="block text-sm font-medium text-gray-400">Forgalmi érvényesség</label>
                <input type="date" 
                       id="registration_expiry" 
                       wire:model="registration_expiry" 
                       class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('registration_expiry') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Alosztály -->
            <div>
                <label for="subdivision_id" class="block text-sm font-medium text-gray-400">Alosztály</label>
                <select id="subdivision_id" 
                        wire:model="subdivision_id" 
                        class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Válassz alosztályt...</option>
                    @foreach($this->subdivisions as $subdivision)
                        <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                    @endforeach
                </select>
                @error('subdivision_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Rang -->
            <div>
                <label for="rank_id" class="block text-sm font-medium text-gray-400">Rang</label>
                <select id="rank_id" 
                        wire:model="rank_id" 
                        class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Válassz rangot...</option>
                    @foreach($this->ranks as $rank)
                        <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                    @endforeach
                </select>
                @error('rank_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tulajdonosok -->
            <div class="col-span-2">
                <label for="owner_ids" class="block text-sm font-medium text-gray-400">Tulajdonosok (max. 2)</label>
                <select id="owner_ids" 
                        wire:model="owner_ids" 
                        multiple
                        class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        size="3">
                    @foreach($this->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('owner_ids') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Figyelmeztetések -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-400 mb-2">Figyelmeztetések</label>
                @foreach($warnings as $index => $warning)
                    <div class="flex items-center space-x-2 mb-2">
                        <input type="text" 
                               wire:model="warnings.{{ $index }}" 
                               class="flex-1 bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" wire:click="removeWarning({{ $index }})" class="text-red-400 hover:text-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
                <button type="button" 
                        wire:click="addWarning" 
                        class="mt-2 text-sm text-blue-400 hover:text-blue-300">
                    + Figyelmeztetés hozzáadása
                </button>
                @error('warnings') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Megjegyzés -->
            <div class="col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-400">Megjegyzés</label>
                <textarea id="notes" 
                          wire:model="notes" 
                          rows="3" 
                          class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                @error('notes') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" 
                    wire:click="$dispatch('closeModal')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Mégsem
            </button>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{ $vehicle ? 'Mentés' : 'Hozzáadás' }}
            </button>
        </div>
    </form>
</div>
