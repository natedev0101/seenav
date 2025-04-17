<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-white leading-tight">
            {{ __('Új felhasználó regisztrálása') }}
        </h2>
    </x-slot>

    @session('successful-creation')
        <div class="bg-green-500/10 text-green-400 p-4 rounded-lg mb-4" role="alert">
            {{ session('successful-creation') }}
        </div>
    @endsession

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Információs buborék -->
            <div class="mb-6 bg-blue-500/10 text-blue-400 p-4 rounded-lg border border-blue-400/20">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="font-medium">Fontos információ</p>
                        <p class="text-sm text-blue-300">A felhasználónév és jelszó automatikusan generálásra kerül a regisztráció során.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('admin.store_user') }}" class="space-y-6">
                        @csrf

                        <!-- IC név -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <x-input-label for="charactername" :value="__('IC név')" />
                            </div>
                            <x-text-input id="charactername" class="block mt-1 w-full" type="text" name="charactername" :value="old('charactername')" required autofocus />
                            <x-input-error :messages="$errors->get('charactername')" class="mt-2" />
                        </div>

                        <!-- Karakter ID -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <x-input-label for="character_id" :value="__('Karakter ID')" />
                            </div>
                            <div class="relative">
                                <div class="flex items-center space-x-2">
                                    <x-text-input 
                                        id="character_id" 
                                        class="block w-32" 
                                        type="number" 
                                        name="character_id" 
                                        :value="old('character_id')" 
                                        x-data
                                        x-on:input="document.getElementById('character_url_preview').textContent = 'https://ucp.see-game.com/v4/character/' + $event.target.value"
                                    />
                                    <span class="text-gray-400">→</span>
                                    <div class="bg-gray-700/50 p-2 rounded-lg flex-1">
                                        <span class="text-gray-400" id="character_url_preview">https://ucp.see-game.com/v4/character/</span>
                                    </div>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('character_id')" class="mt-2" />
                        </div>

                        <!-- Rang -->
                        <div class="space-y-2">
                            <div class="flex items-start space-x-2">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <x-input-label for="rank_id" :value="__('Rang')" />
                                    <select name="rank_id" id="rank_id" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Válassz rangot...</option>
                                        @foreach($ranks as $rank)
                                            <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('rank_id')" class="mt-2" />
                        </div>

                        <!-- Alosztály -->
                        <div class="space-y-2">
                            <div class="flex items-start space-x-2">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1H3v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <x-input-label for="subdivision_id" :value="__('Alosztály')" />
                                    <select name="subdivision_id" id="subdivision_id" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Válassz alosztályt...</option>
                                        @foreach($subdivisions as $subdivision)
                                            <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('subdivision_id')" class="mt-2" />
                        </div>

                        <!-- Játszott perc -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <x-input-label for="played_minutes" :value="__('Játszott perc')" />
                            </div>
                            <x-text-input id="played_minutes" class="block mt-1 w-full" type="number" name="played_minutes" :value="old('played_minutes', 0)" required />
                            <x-input-error :messages="$errors->get('played_minutes')" class="mt-2" />
                        </div>

                        <!-- Telefonszám -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <x-input-label for="phone_number" :value="__('Telefonszám')" />
                            </div>
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Jelvényszám -->
                        <div class="space-y-2">
                            <div class="flex items-start space-x-4">
                                <div class="flex-1 space-y-4">
                                    <div class="flex items-start space-x-2">
                                        <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <x-input-label for="badge_number" :value="__('Jelvényszám')" class="!mb-0" />
                                            <x-text-input id="badge_number" class="block mt-1.5 w-full" type="text" name="badge_number" :value="old('badge_number')" required />
                                            <x-input-error :messages="$errors->get('badge_number')" class="mt-2" />
                                        </div>
                                    </div>

                                    <!-- Utolsó jelvényszám információs doboz -->
                                    <div class="bg-gray-800 rounded-lg shadow-md p-4 space-y-3">
                                        <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Utolsó kiadott jelvényszám</h3>
                                            <div class="bg-blue-500/10 p-1 rounded-lg">
                                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        @if($lastBadge)
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-400">Jelvényszám:</span>
                                                    <span class="text-sm font-medium text-white">{{ $lastBadge->badge_number }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-400">Tulajdonos:</span>
                                                    <span class="text-sm text-white">{{ $lastBadge->assigned_to }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-400">Létrehozta:</span>
                                                    <span class="text-sm text-white">{{ $lastBadge->username }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-400">Időpont:</span>
                                                    <span class="text-sm text-white">{{ $lastBadge->created_at->format('Y.m.d H:i') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center py-3">
                                                <span class="text-sm text-gray-400">Jelenleg még egy jelvény sem került kiadásra</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ki ajánlotta be -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <x-input-label for="recommended_by" :value="__('Ki ajánlotta be')" />
                            </div>
                            <x-text-input id="recommended_by" class="block mt-1 w-full" type="text" name="recommended_by" :value="old('recommended_by')" required />
                            <x-input-error :messages="$errors->get('recommended_by')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <a href="{{ url()->previous() }}" class="bg-gray-500/10 hover:bg-gray-500/20 text-gray-400 hover:text-gray-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>{{ __('Vissza') }}</span>
                            </a>

                            <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>{{ __('Regisztráció') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    // function updateCharacterUrl(value) {
    //     const baseUrl = 'https://ucp.see-game.com/v4/character/';
    //     document.getElementById('full_character_url').textContent = baseUrl + value;
    // }
</script>
@endpush