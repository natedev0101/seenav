<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-medium text-white">
                {{ __('Webmester Vezérlőpult') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6">
                <!-- 2FA Kezelés Kártya -->
                <div class="bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="flex flex-col items-center">
                        <div class="bg-blue-500/10 p-3 rounded-lg mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">2FA Követelmény Kezelése</h3>
                        <p class="text-gray-400 text-sm text-center mb-4">
                            Állítsa be, hogy kötelező legyen-e a kétfaktoros hitelesítés az adminisztrátorok számára
                        </p>

                        <!-- Státusz és Toggle Gomb -->
                        <div class="flex flex-col items-center space-y-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-400">Jelenlegi állapot:</span>
                                <span class="px-2 py-1 rounded-md text-sm {{ $isEnabled ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                    {{ $isEnabled ? 'Aktív' : 'Inaktív' }}
                                </span>
                            </div>
                            <form action="{{ route('superadmin.webmester.2fa.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="enabled" value="{{ $isEnabled ? '0' : '1' }}">
                                <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors">
                                    {{ $isEnabled ? '2FA Kikapcsolása' : '2FA Bekapcsolása' }}
                                </button>
                            </form>
                        </div>

                        <!-- Érintett Felhasználók Lista -->
                        <div class="mt-6 w-full">
                            <h4 class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-3">Érintett Felhasználók</h4>
                            <div class="bg-gray-900/50 rounded-lg p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($admins as $admin)
                                        <a href="{{ route('users.show', $admin->id) }}" 
                                           class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $admin->two_factor_required ? 'bg-green-500/10 text-green-400 hover:bg-green-500/20' : 'bg-gray-500/10 text-gray-400 hover:bg-gray-500/20' }} transition-colors">
                                            {{ $admin->username }}
                                            <svg class="w-4 h-4 ml-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @if(session('status'))
                            <div class="mt-4 p-4 rounded-lg {{ session('status')['type'] === 'success' ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                {{ session('status')['message'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
