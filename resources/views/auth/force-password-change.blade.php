<x-guest-layout>
    <div class="bg-gray-800/50 backdrop-blur-sm p-6 rounded-lg border border-gray-700/50 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="bg-yellow-500/10 w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-medium text-white">{{ __('Kötelező jelszóváltoztatás') }}</h2>
            <p class="mt-2 text-sm text-gray-400">{{ __('A jelszavad visszaállításra került. Kérjük, adj meg egy új jelszót a folytatáshoz.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.force-change.store') }}" class="space-y-4">
            @csrf

            <!-- Új jelszó -->
            <div>
                <x-input-label for="password" :value="__('Új jelszó')" class="text-white" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700/30 border-0 text-white" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Jelszó megerősítése -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Jelszó megerősítése')" class="text-white" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700/30 border-0 text-white" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ __('Jelszó módosítása') }}</span>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
