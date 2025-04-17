<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Fejléc -->
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-white">Új jelentés létrehozása</h2>
                <p class="mt-1 text-sm text-gray-400">Töltsd ki az alábbi mezőket a jelentés létrehozásához.</p>
            </div>

            <!-- Űrlap -->
            <div class="bg-gray-800/50 rounded-lg shadow-lg overflow-hidden">
                <form action="{{ route('reports.store') }}" method="POST" class="p-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Űrlap mezők -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bal oldal -->
                            <div class="space-y-4">
                                <!-- Elkövető neve -->
                                <div>
                                    <label for="suspect_name" class="block text-sm font-medium text-gray-300">
                                        Elkövető neve <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="suspect_name" id="suspect_name" required
                                        class="mt-1 block w-full bg-gray-700/50 border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-white @error('suspect_name') border-red-500 @enderror"
                                        value="{{ old('suspect_name') }}">
                                    @error('suspect_name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jelentés típusa -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-300">
                                        Jelentés típusa <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full bg-gray-700/50 border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-white @error('type') border-red-500 @enderror">
                                        <option value="">Válassz típust...</option>
                                        <option value="ELŐÁLLÍTÁS" {{ old('type') == 'ELŐÁLLÍTÁS' ? 'selected' : '' }}>Előállítás</option>
                                        <option value="IGAZOLTATÁS" {{ old('type') == 'IGAZOLTATÁS' ? 'selected' : '' }}>Igazoltatás</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bírság összege -->
                                <div x-data="{ 
                                    formattedAmount: '', 
                                    rawAmount: {{ old('fine_amount', 0) }},
                                    formatAmount(value) {
                                        // Eltávolítjuk a nem szám karaktereket
                                        let number = value.replace(/[^0-9]/g, '');
                                        
                                        // Maximum 300,000
                                        number = Math.min(parseInt(number) || 0, 300000);
                                        
                                        // Beállítjuk a rejtett input értékét
                                        this.rawAmount = number;
                                        
                                        // Formázás ezres elválasztóval
                                        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    }
                                }">
                                    <label for="fine_amount_display" class="block text-sm font-medium text-gray-300">
                                        Bírság összege <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-lg shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-400 sm:text-sm">$</span>
                                        </div>
                                        <input type="text" 
                                               id="fine_amount_display"
                                               x-model="formattedAmount"
                                               @input="formattedAmount = formatAmount($event.target.value)"
                                               class="block w-full pl-7 bg-gray-700/50 border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-white @error('fine_amount') border-red-500 @enderror"
                                               placeholder="Maximum: 300.000">
                                        
                                        <!-- Rejtett mező a tényleges értékkel -->
                                        <input type="hidden" 
                                               name="fine_amount" 
                                               x-model="rawAmount">
                                    </div>
                                    @error('fine_amount')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-400">Maximum összeg: $300.000</p>
                                </div>

                                <!-- Jelentés dátuma -->
                                <div>
                                    <label for="report_date" class="block text-sm font-medium text-gray-300">
                                        Jelentés dátuma <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-lg shadow-sm">
                                        <input type="date" 
                                               name="report_date" 
                                               id="report_date" 
                                               required
                                               min="{{ now()->subDays(6)->format('Y-m-d') }}"
                                               max="{{ now()->format('Y-m-d') }}"
                                               value="{{ old('report_date', now()->format('Y-m-d')) }}"
                                               class="block w-full bg-gray-700/50 border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-white @error('report_date') border-red-500 @enderror">
                                    </div>
                                    @error('report_date')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-400">Maximum 6 nappal korábbi dátum választható</p>
                                </div>

                                <!-- Leadás dátuma -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">
                                        Leadás dátuma
                                    </label>
                                    <div class="mt-1 relative rounded-lg shadow-sm">
                                        <input type="text" 
                                               value="{{ now()->format('Y-m-d H:i') }}"
                                               disabled
                                               class="block w-full bg-gray-700/50 border-gray-600 rounded-lg shadow-sm text-gray-400">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400">Automatikusan rögzítve</p>
                                </div>
                            </div>

                            <!-- Jobb oldal -->
                            <div>
                                <!-- Járőrtársak -->
                                <div x-data="userSearch()" class="mb-4">
                                    <label for="search" class="block text-sm font-medium text-gray-300">
                                        Járőrtársak <span class="text-gray-400">(maximum 2)</span>
                                    </label>

                                    <!-- Kereső mező -->
                                    <div class="mt-1 relative">
                                        <input type="text" 
                                               x-model="searchQuery" 
                                               @input.debounce.300ms="searchUsers"
                                               placeholder="Keresés név alapján..."
                                               class="block w-full bg-gray-700/50 border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-white">
                                        
                                        <!-- Találatok -->
                                        <div x-show="searchResults.length > 0" 
                                             x-transition
                                             @click.away="searchResults = []"
                                             class="absolute z-10 mt-1 w-full bg-gray-800 rounded-lg shadow-lg max-h-60 overflow-auto">
                                            <template x-for="user in searchResults" :key="user.id">
                                                <div @click="selectUser(user)"
                                                     class="p-2 text-white flex items-center justify-between cursor-pointer hover:bg-gray-700">
                                                    <div class="flex items-center">
                                                        <span x-text="user.charactername"></span>
                                                        <span x-show="!user.is_on_duty" class="ml-2 text-xs text-yellow-400">(Nincs szolgálatban)</span>
                                                    </div>
                                                    <span x-show="isSelected(user.id)" class="text-green-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Kiválasztott járőrtársak -->
                                    <div class="mt-2 space-y-2">
                                        <template x-for="user in selectedUsers" :key="user.id">
                                            <div class="flex items-center justify-between bg-gray-700/30 p-2 rounded-lg">
                                                <span x-text="user.charactername" class="text-white"></span>
                                                <button @click="removeUser(user)" type="button" 
                                                        class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Rejtett input mezők a kiválasztott járőrtársakhoz -->
                                    <template x-for="user in selectedUsers" :key="user.id">
                                        <input type="hidden" name="partner_ids[]" :value="user.id">
                                    </template>

                                    @error('partner_ids')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kép URL -->
                                <div>
                                    <label for="image_url" class="block text-sm font-medium text-gray-300">
                                        Kép URL <span class="text-gray-400">(később kötelező lesz)</span>
                                    </label>
                                    <input type="url" name="image_url" id="image_url"
                                        class="mt-1 block w-full bg-gray-700/50 border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-white @error('image_url') border-red-500 @enderror"
                                        value="{{ old('image_url') }}"
                                        placeholder="https://...">
                                    @error('image_url')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Gombok -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:space-x-3">
                            <a href="{{ route('reports.index') }}" 
                               class="w-full sm:w-auto bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Mégsem</span>
                            </a>
                            <button type="submit"
                                    class="w-full sm:w-auto bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Létrehozás</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function userSearch() {
            return {
                searchQuery: '',
                searchResults: [],
                selectedUsers: [],
                
                async searchUsers() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    try {
                        const response = await fetch(`{{ route('reports.search-users') }}?query=${this.searchQuery}`);
                        const users = await response.json();
                        this.searchResults = users;
                    } catch (error) {
                        console.error('Hiba történt a keresés során:', error);
                    }
                },

                selectUser(user) {
                    if (this.isSelected(user.id)) {
                        this.removeUser(user);
                    } else if (this.selectedUsers.length < 2) {
                        this.selectedUsers.push(user);
                        this.searchResults = [];
                        this.searchQuery = '';
                    } else {
                        alert('Maximum 2 járőrtársat választhatsz!');
                    }
                },

                removeUser(user) {
                    this.selectedUsers = this.selectedUsers.filter(u => u.id !== user.id);
                },

                isSelected(userId) {
                    return this.selectedUsers.some(user => user.id === userId);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
