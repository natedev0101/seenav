<div>
    <x-modal name="edit-vehicle" wire:model="show">
        <div class="p-6">
            <div class="text-lg font-medium text-white mb-4">
                Jármű szerkesztése
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Rendszám -->
                    <div>
                        <label for="plate_number" class="block text-sm font-medium text-gray-400">Rendszám</label>
                        <input type="text" 
                               id="plate_number"
                               wire:model="plate_number"
                               class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('plate_number') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jármű típus -->
                    <div>
                        <label for="vehicle_type_id" class="block text-sm font-medium text-gray-400">Jármű típusa</label>
                        <select id="vehicle_type_id"
                                wire:model="vehicle_type_id"
                                class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Válassz típust...</option>
                            @foreach($vehicleTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_type_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jármű azonosító -->
                    <div>
                        <label for="veh_id" class="block text-sm font-medium text-gray-400">Jármű azonosító</label>
                        <input type="text" 
                               id="veh_id"
                               wire:model="veh_id"
                               class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('veh_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Forgalmi érvényessége -->
                    <div>
                        <label for="registration_expiry" class="block text-sm font-medium text-gray-400">Forgalmi érvényessége</label>
                        <input type="date" 
                               id="registration_expiry"
                               wire:model="registration_expiry"
                               class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('registration_expiry') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tulajdonosok -->
                    <div class="md:col-span-2">
                        <label for="owner_ids" class="block text-sm font-medium text-gray-400">Tulajdonosok (max. 2)</label>
                        <select id="owner_ids"
                                wire:model="owner_ids"
                                multiple
                                size="3"
                                class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->charactername }}</option>
                            @endforeach
                        </select>
                        @error('owner_ids') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alosztály -->
                    <div>
                        <label for="subdivision_id" class="block text-sm font-medium text-gray-400">Alosztály</label>
                        <select id="subdivision_id"
                                wire:model="subdivision_id"
                                class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Válassz alosztályt...</option>
                            @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                            @endforeach
                        </select>
                        @error('subdivision_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rang -->
                    <div>
                        <label for="rank_id" class="block text-sm font-medium text-gray-400">Rang</label>
                        <select id="rank_id"
                                wire:model="rank_id"
                                class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Válassz rangot...</option>
                            @foreach($ranks as $rank)
                                <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                            @endforeach
                        </select>
                        @error('rank_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Figyelmeztetések -->
                    <div class="md:col-span-2">
                        <label for="warnings" class="block text-sm font-medium text-gray-400">Figyelmeztetések</label>
                        <textarea id="warnings"
                                  wire:model="warnings"
                                  rows="2"
                                  class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Figyelmeztetések, vesszővel elválasztva..."></textarea>
                        @error('warnings') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jegyzetek -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-400">Jegyzetek</label>
                        <textarea id="notes"
                                  wire:model="notes"
                                  rows="3"
                                  class="mt-1 block w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="További megjegyzések..."></textarea>
                        @error('notes') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Mégse
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Mentés
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
