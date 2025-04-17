<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rang részletei:') }} <span style="color: {{ $rank->color }}">{{ $rank->name }}</span>
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg">
                <div class="p-6 text-white space-y-6">
                    <x-back-button />
                    {{-- Kereső panel --}}
                    <div class="mb-6">
                        <form method="GET" action="{{ route('ranks.users', $rank) }}" class="flex gap-4">
                            <div class="flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search }}" 
                                       placeholder="{{ __('Keresés karakternév alapján...') }}"
                                       class="rank-input">
                            </div>
                            <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                {{ __('Keresés') }}
                            </button>
                            @if($search)
                                <a href="{{ route('ranks.users', $rank) }}" 
                                   class="bg-gray-500/10 hover:bg-gray-500/20 text-gray-400 hover:text-gray-300 p-1.5 rounded-lg transition-colors flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ __('Törlés') }}
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- Felhasználók listája --}}
                    <div class="overflow-x-auto">
                        <table class="rank-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Karakternév') }}</th>
                                    <th>{{ __('Utolsó belépés') }}</th>
                                    <th class="text-center">{{ __('Státusz') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td data-label="{{ __('Karakternév') }}">
                                            {{ $user->charactername }}
                                        </td>
                                        <td data-label="{{ __('Utolsó belépés') }}">
                                            {{ $user->last_login ? $user->last_login->diffForHumans() : __('Soha') }}
                                        </td>
                                        <td data-label="{{ __('Státusz') }}" class="text-center">
                                            @if($user->is_online)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400">
                                                    {{ __('Online') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400">
                                                    {{ __('Offline') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-400">
                                            @if($search)
                                                {{ __('Nincs találat a keresésre.') }}
                                            @else
                                                {{ __('Nincs felhasználó ezzel a ranggal.') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Lapozó --}}
                    <div class="mt-4">
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
