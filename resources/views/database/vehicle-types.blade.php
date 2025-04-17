<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Járműtípusok') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
          </svg>      
    </x-slot>
    <div class="container mx-auto px-4 py-8">
        @if(auth()->user()->isOfficer || auth()->user()->isAdmin || auth()->user()->is_szuperadmin)
            <div class="bg-gray-800 shadow-md rounded-lg p-6">
                <livewire:vehicle-type-list />
            </div>
        @else
            <div class="bg-red-500/10 text-red-400 p-4 rounded-lg">
                Nincs jogosultságod az oldal megtekintéséhez!
            </div>
        @endif
    </div>
</x-app-layout>
