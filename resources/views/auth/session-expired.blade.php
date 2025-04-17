<x-guest-layout>
    <div class="mb-4 text-sm text-gray-300">
        <div class="bg-red-500/10 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h2 class="text-lg font-medium text-red-400">Session lejárt</h2>
            </div>
            <p class="mt-2 text-gray-300">
                A munkameneted lejárt inaktivitás miatt. Kérjük, jelentkezz be újra a folytatáshoz.
            </p>
        </div>
    </div>

    <div class="flex justify-center">
        <a href="{{ route('login') }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            <span>Bejelentkezés</span>
        </a>
    </div>
</x-guest-layout>
