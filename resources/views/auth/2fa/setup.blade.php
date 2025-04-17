<x-auth-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-12">
                    </div>
                    
                    <h2 class="text-2xl font-semibold mb-6 text-center">Két faktoros hitelesítés beállítása</h2>
                    
                    @if(session('warning'))
                        <div class="bg-yellow-500/10 text-yellow-400 p-4 rounded-lg mb-6">
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <p class="text-gray-300 mb-4">
                            A két faktoros hitelesítés extra biztonságot nyújt a fiókjának. Miután engedélyezi, 
                            bejelentkezéskor a jelszaván kívül egy időalapú kódot is meg kell majd adnia.
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-medium mb-4">1. Telepítse a Google Authenticator alkalmazást</h3>
                        <p class="text-gray-300 mb-2">
                            Töltse le a Google Authenticator alkalmazást:
                        </p>
                        <div class="flex space-x-4 mb-4">
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                               target="_blank"
                               class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.523 2H6.477C5.108 2 4 3.108 4 4.477v15.046C4 20.892 5.108 22 6.477 22h11.046C18.892 22 20 20.892 20 19.523V4.477C20 3.108 18.892 2 17.523 2zM8.5 4h7a.5.5 0 010 1h-7a.5.5 0 010-1zm3.5 17.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                                </svg>
                                Android
                            </a>
                            <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" 
                               target="_blank"
                               class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                </svg>
                                iPhone
                            </a>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-medium mb-4">2. Olvassa be a QR kódot</h3>
                        <div class="bg-white p-4 inline-block rounded-lg mb-4">
                            <img src="{{ $qrCodeSvg }}" alt="QR Code" class="w-48 h-48">
                        </div>
                        <p class="text-gray-300 mb-2">
                            Ha nem tudja beolvasni a QR kódot, használja ezt a kódot:
                        </p>
                        <code class="bg-gray-700 px-4 py-2 rounded-lg text-gray-300">{{ $secret }}</code>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-medium mb-4">3. Adja meg az alkalmazásban megjelenő kódot</h3>
                        <form method="POST" action="{{ route('2fa.enable') }}" class="max-w-md">
                            @csrf
                            <div class="mb-4">
                                <label for="code" class="block text-sm font-medium text-gray-300 mb-2">
                                    Hitelesítő kód
                                </label>
                                <input type="text" 
                                       id="code" 
                                       name="code" 
                                       required 
                                       maxlength="6"
                                       class="bg-gray-700 border border-gray-600 text-white rounded-lg block w-full p-2.5"
                                       placeholder="123456">
                                @error('code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" 
                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hitelesítés engedélyezése
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
