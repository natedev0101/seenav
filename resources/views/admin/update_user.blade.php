<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 text-white leading-tight">
            {{ __('Felhasználó frissítése') }}
        </h2>
    </x-slot>

    @session('password-updated')
        <div class="alert alert-success" role="alert">
            {{ session('password-updated') }}
        </div>
    @endsession

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 create-report">
            <div class="bg-gray-800 bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white text-white">
                    <p class="top5">Csak azok az adatok frissülnek, amik megváltoznak.</p>
                    <form method="POST" action="{{ route('admin.updateUser', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Character name -->
                        <div>
                            <x-input-label for="charactername" :value="__('IC név')" />
                            <x-text-input id="charactername" class="block mt-1 w-full" type="text" name="charactername" value="{{ $user->charactername }}" maxlength="255" autofocus />
                            <x-input-error :messages="$errors->get('charactername')" class="mt-2" />
                        </div>

                        <!-- Username -->
                        <div class="mt-4">
                            <x-input-label for="username" :value="__('Felhasználónév')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ $user->username }}" maxlength="255" autofocus />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        @if (Auth::user()->canGiveAdmin == 1)
                            <div class="mt-4">
                                <div class="form-check form-check-inline checkbox">
                                    @if ($user->username == Auth::user()->username)
                                        <input type="checkbox" id="admin" name="admin" value="admin" disabled checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 form-check-input">
                                    @else
                                        @if ($user->isAdmin == 1)
                                            <input type="checkbox" id="admin" name="admin" value="admin" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 form-check-input">
                                        @else
                                            <input type="checkbox" id="admin" name="admin" value="admin" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 form-check-input">
                                        @endif
                                    @endif
                                    <label for="admin" class="form-check-label"> Admin</label><br>
                                </div>
                            </div>
                        @endif


                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Frissítés') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.updateUserPassword', $user->id) }}">
                        @csrf
                        @method('PUT')                        
                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Új jelszó')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" maxlength="255" autofocus />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Password re -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Új jelszó újra')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" maxlength="255" autofocus />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.index') }}">
                                <x-secondary-button>
                                    {{ __('Vissza') }}
                                </x-secondary-button>
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Jelszó frissítés') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>