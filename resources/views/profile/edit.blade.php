<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <h2 class="text-lg font-medium text-white">{{ __('Profil beállítások') }}</h2>
        </div>
    </x-slot>

    <div class="py-6 min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-flash-message class="mb-4" />
            
            <!-- Profil grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Bal oldali oszlop -->
                <div class="space-y-4">
                    <!-- Profilkép kártya -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 border border-gray-700/50">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-white">{{ __('Profilkép') }}</span>
                            </div>
                            @if(Auth::user()->profile_picture)
                                <form action="{{ route('profile.removePicture') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-all duration-300 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="flex justify-center">
                            <div x-data="{ isUploading: false }" class="relative">
                                <form method="POST" action="{{ route('profile.updatePicture') }}" enctype="multipart/form-data">
                                    @csrf
                                    <label class="cursor-pointer block relative group">
                                        <div class="relative w-28 h-28 rounded-lg overflow-hidden bg-gradient-to-br from-gray-700 to-gray-800 ring-2 ring-gray-700/50 transition-all duration-300 group-hover:ring-blue-500/30">
                                            <img 
                                                src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-profile.png') }}" 
                                                alt="{{ __('Profilkép') }}" 
                                                class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                            >
                                            
                                            <!-- Feltöltés overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/50 to-transparent flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                <div class="bg-blue-500/20 p-2 rounded-lg backdrop-blur-sm">
                                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Betöltés jelző -->
                                            <div x-show="isUploading" class="absolute inset-0 bg-gradient-to-b from-gray-900/80 to-gray-900/50 backdrop-blur-sm flex items-center justify-center">
                                                <div class="bg-blue-500/20 p-2 rounded-lg">
                                                    <svg class="w-6 h-6 text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="file" 
                                            name="profile_picture" 
                                            class="hidden" 
                                            accept="image/*"
                                            @change="isUploading = true; $el.form.submit()">
                                    </label>
                                </form>
                            </div>
                        </div>
                        
                        <p class="mt-2 text-xs text-center text-gray-400">
                            {{ __('Kattints a képre a módosításhoz') }}
                        </p>
                    </div>

                    <!-- Felhasználói statisztikák és jelszó módosítás -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 border border-gray-700/50">
                        <div class="grid grid-cols-2 gap-3 mb-3">
                        </div>

                        <!-- Jelszó módosítás -->
                        <form method="post" action="{{ route('profile.updatePassword', ['id' => $user->id]) }}" class="space-y-3">
                            @csrf
                            @method('patch')
                            
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">{{ __('Jelszó módosítása') }}</span>
                                </div>
                                <input type="password" 
                                    name="current_password" 
                                    class="w-full bg-gray-700/30 border-0 rounded-lg focus:ring-2 focus:ring-blue-500/50 text-white text-sm placeholder-gray-500" 
                                    placeholder="{{ __('Jelenlegi jelszó') }}"
                                    required />
                                <input type="password" 
                                    name="new_password" 
                                    class="w-full bg-gray-700/30 border-0 rounded-lg focus:ring-2 focus:ring-blue-500/50 text-white text-sm placeholder-gray-500" 
                                    placeholder="{{ __('Új jelszó') }}"
                                    required />
                                <input type="password" 
                                    name="new_password_confirmation" 
                                    class="w-full bg-gray-700/30 border-0 rounded-lg focus:ring-2 focus:ring-blue-500/50 text-white text-sm placeholder-gray-500" 
                                    placeholder="{{ __('Új jelszó megerősítése') }}"
                                    required />
                                <x-input-error class="mt-1" :messages="$errors->get('current_password')" />
                                <x-input-error class="mt-1" :messages="$errors->get('new_password')" />
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-3 py-1.5 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-1.5 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    <span>{{ __('Jelszó módosítása') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Jobb oldali oszlop -->
                <div class="space-y-4">
                    <!-- Felhasználói adatok -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 border border-gray-700/50">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-white">{{ __('Felhasználói adatok') }}</span>
                        </div>

                        <!-- Felhasználónév -->
                        <form method="post" action="{{ route('profile.updateUsername', ['id' => $user->id]) }}" class="mb-6">
                            @csrf
                            @method('patch')
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">{{ __('Felhasználónév') }}</span>
                                </div>
                                <input type="text" 
                                    name="username" 
                                    value="{{ old('username', $user->username) }}"
                                    class="w-full bg-gray-700/30 border-0 rounded-lg focus:ring-2 focus:ring-blue-500/50 text-white text-sm placeholder-gray-500" 
                                    placeholder="{{ __('Felhasználónév') }}" />
                                <p class="mt-1 text-xs text-gray-400">{{ __('Ez a név jelenik meg a profilodban és a kommentjeidnél. Csak betűket és számokat tartalmazhat.') }}</p>
                                <x-input-error class="mt-1" :messages="$errors->get('username')" />
                                
                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-3 py-1.5 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-1.5 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ __('Felhasználónév módosítása') }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- InGame név -->
                        <form method="post" action="{{ route('profile.updateCharacterName', ['id' => $user->id]) }}">
                            @csrf
                            @method('patch')
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-white">{{ __('InGame név') }}</span>
                                </div>
                                @if (Auth::user()->isAdmin || Auth::user()->isSuperAdmin)
                                    <input type="text" 
                                        name="charactername" 
                                        value="{{ old('charactername', $user->charactername) }}"
                                        class="w-full bg-gray-700/30 border-0 rounded-lg focus:ring-2 focus:ring-blue-500/50 text-white text-sm placeholder-gray-500" 
                                        placeholder="{{ __('InGame név') }}" />
                                    <p class="mt-1 text-xs text-gray-400">{{ __('A játékban használt karakternév. Csak betűket tartalmazhat és egyedinek kell lennie.') }}</p>
                                    <x-input-error class="mt-1" :messages="$errors->get('charactername')" />
                                    
                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-3 py-1.5 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-1.5 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ __('InGame név módosítása') }}</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="w-full bg-gray-700/30 px-3 py-2 rounded-lg text-sm text-white">
                                        {{ $user->charactername }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400">{{ __('A játékban használt karakterneved. Ezt csak az adminisztrátorok módosíthatják.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
