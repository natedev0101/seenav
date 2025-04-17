<x-auth-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('/images/nav.png') }}" alt="Logo" class="h-12">
                    </div>

                    <h2 class="text-2xl font-semibold mb-6 text-center">2FA Hitelesítés - Leader | Webmester</h2>

                    <div class="max-w-md mx-auto">
                        <p class="text-gray-300 mb-6">
                            Add meg a Google Authenticator alkalmazásban megjelenő kódot a folytatáshoz.
                        </p>

                        <form method="POST" action="{{ route('2fa.verify') }}">
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
                                       pattern="[0-9]*"
                                       inputmode="numeric"
                                       class="bg-gray-700 border border-gray-600 text-white rounded-lg block w-full p-2.5"
                                       placeholder="123456"
                                       autofocus
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" 
                                    class="w-full bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center gap-2 mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Jóváhagyás
                            </button>
                        </form>

                        <button type="button"
                                id="helpButton"
                                class="w-full bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kérj segítséget!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segítségkérés Modal -->
    <div id="helpModal" class="fixed inset-0 z-50 hidden">
        <!-- Háttér overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50" id="modalOverlay"></div>
        
        <!-- Modal tartalom -->
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl max-w-lg w-full mx-auto">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-white mb-4">
                        Segítségkérés a Webmestertől
                    </h3>
                    
                    <div class="mb-4">
                        <label for="problem" class="block text-sm font-medium text-gray-300 mb-2">
                            Írja le a problémát részletesen
                        </label>
                        <textarea id="problem" 
                                rows="4" 
                                class="bg-gray-700 border border-gray-600 text-white rounded-lg block w-full p-2.5"
                                placeholder="Pl.: Nem tudom beállítani a Google Authenticator alkalmazást..."></textarea>
                        <p id="errorMessage" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button"
                                id="cancelButton"
                                class="bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white px-4 py-2 rounded-lg transition-colors">
                            Mégse
                        </button>
                        <button type="button"
                                id="sendButton"
                                class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Küldés
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        console.log('Script started');
        
        // DOM elemek
        const modal = document.getElementById('helpModal');
        const helpButton = document.getElementById('helpButton');
        const cancelButton = document.getElementById('cancelButton');
        const sendButton = document.getElementById('sendButton');
        const modalOverlay = document.getElementById('modalOverlay');
        const problemInput = document.getElementById('problem');
        const errorMessage = document.getElementById('errorMessage');

        console.log('Elements found:', {
            modal, helpButton, cancelButton, sendButton, modalOverlay, problemInput, errorMessage
        });

        // Modál megjelenítése
        function showModal() {
            console.log('Showing modal');
            modal.classList.remove('hidden');
        }

        // Modál elrejtése
        function hideModal() {
            console.log('Hiding modal');
            modal.classList.add('hidden');
            problemInput.value = '';
            errorMessage.classList.add('hidden');
        }

        // Űrlap küldése
        async function sendRequest() {
            console.log('Sending request...');
            const problem = problemInput.value.trim();
            
            if (!problem) {
                errorMessage.textContent = 'Kérjük, írja le a problémát!';
                errorMessage.classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch('{{ route("help.request.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ problem })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message);
                    hideModal();
                } else {
                    throw new Error(data.message || 'Hiba történt a küldés során.');
                }
            } catch (error) {
                console.error('Error:', error);
                errorMessage.textContent = error.message;
                errorMessage.classList.remove('hidden');
            }
        }

        // Eseménykezelők hozzáadása
        helpButton.addEventListener('click', showModal);
        cancelButton.addEventListener('click', hideModal);
        sendButton.addEventListener('click', sendRequest);
        modalOverlay.addEventListener('click', hideModal);

        // ESC gomb kezelése
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });

        // Enter gomb kezelése
        problemInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendRequest();
            }
        });

        console.log('Script initialized');
    </script>
</x-auth-layout>
