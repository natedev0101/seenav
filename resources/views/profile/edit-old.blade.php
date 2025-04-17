<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-medium text-white">
            {{ __('Profil beállítások') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profilkép szekció -->
            <div class="profile-card">
                <div class="max-w-xl">
                    <section class="profile-section">
                        <x-flash-message class="mb-4" />

                        <div class="profile-header">
                            <h2 class="profile-title">{{ __('Profilkép') }}</h2>
                            <p class="profile-subtitle">
                                {{ __('Tölts fel egy profilképet, amely megjelenik a profilodon.') }}
                            </p>
                        </div>

                        <!-- Profilkép megjelenés és feltöltés -->
                        <div class="mt-6 flex flex-col items-center">
                            <div x-data="{ isUploading: false }" class="relative">
                                <form method="POST" action="{{ route('profile.updatePicture') }}" enctype="multipart/form-data">
                                    @csrf
                                    <label class="profile-picture-container">
                                        <img 
                                            src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-profile.png') }}" 
                                            alt="{{ __('Profilkép') }}" 
                                            class="profile-picture"
                                        >
                                        
                                        <!-- Feltöltés ikon overlay -->
                                        <div class="profile-picture-upload-overlay">
                                            <div class="bg-blue-500/20 p-3 rounded-lg">
                                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Betöltés jelző -->
                                        <div x-show="isUploading" class="profile-picture-loading">
                                            <div class="bg-blue-500/20 p-3 rounded-lg">
                                                <svg class="w-8 h-8 text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <input type="file" 
                                            name="profile_picture" 
                                            class="hidden" 
                                            accept="image/*"
                                            @change="isUploading = true; $el.form.submit()">
                                    </label>
                                </form>

                                @if(Auth::user()->profile_picture)
                                    <form action="{{ route('profile.removePicture') }}" method="POST" class="absolute -bottom-2 -right-2">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <p class="mt-2 text-sm text-gray-400">
                                {{ __('Kattints a képre a módosításhoz') }}
                            </p>
                        </div>
                    </section>
                </div>
            </div>

            <div class="profile-section-divider"></div>

            <!-- Profil információk szekció -->
            <div class="profile-card">
                <div class="max-w-xl">
                    <section class="profile-section">
                        <div class="profile-header">
                            <div class="flex items-center space-x-2">
                                <svg class="panel-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h2 class="profile-title">{{ __('Profil Információk') }}</h2>
                            </div>
                            <p class="profile-subtitle">
                                {{ __('Felhasználói adatok megtekintése és módosítása.') }}
                            </p>
                        </div>

                        <div class="panel-body">
                            <form method="post" action="{{ route('profile.update', ['id' => $user->id]) }}" class="space-y-6">
                                @csrf
                                @method('patch')

                                <div class="info-list">
                                    <div class="info-list-item">
                                        <div class="info-group">
                                            <label for="name" class="info-label">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>{{ __('Felhasználónév') }}</span>
                                            </label>
                                            <p class="info-description">{{ __('Ez a név jelenik meg a profilodban és a kommentjeidnél.') }}</p>
                                            <div>
                                                <x-text-input id="name" name="name" type="text" class="profile-input" :value="old('name', $user->username)" required autofocus />
                                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-list-item">
                                        <div class="info-group">
                                            <label for="charactername" class="info-label">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                <span>{{ __('InGame név') }}</span>
                                            </label>
                                            <p class="info-description">{{ __('A játékban használt karakterneved. Ezt csak az adminisztrátorok módosíthatják.') }}</p>
                                            <div class="flex items-center space-x-2">
                                                @if (Auth::user()->isAdmin || Auth::user()->isSuperAdmin)
                                                    <x-text-input id="charactername" name="charactername" type="text" class="profile-input" :value="old('charactername', $user->charactername)" />
                                                @else
                                                    <div class="profile-input-readonly">
                                                        {{ $user->charactername }}
                                                    </div>
                                                @endif
                                                @if (!(Auth::user()->isAdmin || Auth::user()->isSuperAdmin))
                                                    <span class="info-badge info-badge-yellow">{{ __('Csak admin módosíthatja') }}</span>
                                                @endif
                                                <x-input-error class="mt-2" :messages="$errors->get('charactername')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-4">
                                    <button type="submit" class="profile-button-primary">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ __('Mentés') }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

            <div class="profile-section-divider"></div>

            <!-- Jelszó frissítése szekció -->
            <div class="profile-card">
                <div class="max-w-xl">
                    <section class="profile-section">
                        <div class="profile-header">
                            <div class="flex items-center space-x-2">
                                <svg class="panel-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                <h2 class="profile-title">{{ __('Jelszó Frissítése') }}</h2>
                            </div>
                            <p class="profile-subtitle">
                                {{ __('Győződj meg róla, hogy fiókod biztonságos jelszót használ.') }}
                            </p>
                        </div>

                        <div class="panel-body">
                            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                                @csrf
                                @method('put')

                                <div class="info-list">
                                    <div class="info-list-item">
                                        <div class="info-group">
                                            <label for="current_password" class="info-label">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                <span>{{ __('Jelenlegi Jelszó') }}</span>
                                            </label>
                                            <p class="new-info-description">{{ __('A biztonság érdekében add meg a jelenlegi jelszavad.') }}</p>
                                            <div>
                                                <x-text-input id="current_password" name="current_password" type="password" class="profile-input" autocomplete="current-password" />
                                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-list-item">
                                        <div class="info-group">
                                            <label for="password" class="info-label">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ __('Új Jelszó') }}</span>
                                            </label>
                                            <p class="new-info-description">{{ __('Az új jelszavadnak legalább 8 karakter hosszúnak kell lennie.') }}</p>
                                            <div>
                                                <x-text-input id="password" name="password" type="password" class="profile-input" autocomplete="new-password" />
                                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-list-item">
                                        <div class="info-group">
                                            <label for="password_confirmation" class="info-label">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                <span>{{ __('Jelszó Megerősítése') }}</span>
                                            </label>
                                            <p class="new-info-description">{{ __('A biztonság érdekében add meg újra az új jelszavad.') }}</p>
                                            <div>
                                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="profile-input" autocomplete="new-password" />
                                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-4">
                                    <button type="submit" class="profile-button-primary">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ __('Jelszó Módosítása') }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>