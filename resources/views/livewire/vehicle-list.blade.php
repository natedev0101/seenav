<div>
    <div class="mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            @if(auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
                <div class="w-full md:w-1/2">
                    <button wire:click="$dispatch('openCreateVehicleModal')" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            @endif
            <div class="w-full md:w-1/2">
                <div class="relative">
                    <input type="text" 
                           wire:model.live="search" 
                           class="w-full bg-gray-700 text-white rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Keresés...">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="simple-table">
            <thead>
                <tr>
                    <th class="w-16">#</th>
                    <th>Rendszám</th>
                    <th>Típus</th>
                    <th>Azonosító</th>
                    <th>Tulajdonosok</th>
                    <th>Alosztály</th>
                    <th>Rang</th>
                    <th>Forgalmi érvényessége</th>
                    @if(auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
                        <th class="w-24">Műveletek</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vehicle->plate_number }}</td>
                        <td>{{ $vehicle->vehicleType->name }}</td>
                        <td>{{ $vehicle->veh_id }}</td>
                        <td>{{ $vehicle->owners->pluck('charactername')->join(', ') }}</td>
                        <td>{{ $vehicle->subdivision->name }}</td>
                        <td>{{ $vehicle->rank->name }}</td>
                        <td>{{ $vehicle->registration_expiry->format('Y.m.d') }}</td>
                        @if(auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
                            <td>
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="$dispatch('openEditVehicleModal', { id: {{ $vehicle->id }} })" 
                                            class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $vehicle->id }})" 
                                            onclick="return confirm('Biztosan törölni szeretnéd ezt a járművet?')"
                                            class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin || auth()->user()->is_szuperadmin ? 9 : 8 }}" class="text-center py-4 text-gray-400">
                            Nincs még jármű az adatbázisban
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $vehicles->links() }}
    </div>

    <livewire:create-vehicle-modal />
    <livewire:edit-vehicle-modal />
</div>
