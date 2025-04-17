<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Járműnyílvántartás') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if(auth()->user()->isOfficer || auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
            <div class="flex justify-between mb-4">
                <button type="button"
                        x-data
                        x-on:click="$dispatch('open-modal', 'create-vehicle')"
                        class="flex items-center space-x-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Jármű regisztrálása</span>
                </button>
                <a href="{{ route('vehicle-types.index') }}" class="flex items-center space-x-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Járműtípusok kezelése</span>
                </a>
            </div>
            <div class="bg-gray-800 shadow-md rounded-lg p-6">
                <livewire:vehicle-list />
            </div>
        @else
            <div class="bg-red-500/10 text-red-400 p-4 rounded-lg">
                Nincs jogosultságod az oldal megtekintéséhez!
            </div>
        @endif
    </div>

    <livewire:create-vehicle-modal />
</x-app-layout>
