<div>
    <div class="mb-6">
        <h1 class="text-xl font-medium text-white">Járműtípusok</h1>
    </div>

    @if(auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
        <div class="mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/2">
                    <form wire:submit.prevent="save" class="flex items-center space-x-2">
                        <div class="flex-1">
                            <input type="text" 
                                   wire:model.live="name" 
                                   placeholder="Új járműtípus neve..." 
                                   class="w-full bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" 
                                class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $name && strlen(trim($name)) < 2 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </form>
                </div>
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
    @else
        <div class="mb-6">
            <div class="relative w-full md:w-1/2">
                <input type="text" 
                       wire:model.live="search" 
                       class="w-full bg-gray-700 text-white rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="Keresés...">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="simple-table">
            <thead>
                <tr>
                    <th class="w-16">#</th>
                    <th>Név</th>
                    @if(auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
                        <th class="w-24">Műveletek</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($editingId === $type->id)
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           wire:model.live="editingName" 
                                           class="flex-1 bg-gray-700 text-white rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button wire:click="updateType" 
                                            class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ strlen(trim($editingName)) < 2 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="$set('editingId', null)" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('editingName') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            @else
                                {{ $type->name }}
                            @endif
                        </td>
                        @if(auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
                            <td>
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="startEditing({{ $type->id }})" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $type->id }})" 
                                            onclick="return confirm('Biztosan törölni szeretnéd ezt a járműtípust?')"
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
                        <td colspan="{{ auth()->user()->isAdmin || auth()->user()->is_szuperadmin ? 3 : 2 }}" class="text-center py-4 text-gray-400">
                            Nincs még járműtípus az adatbázisban
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $types->links() }}
    </div>
</div>
