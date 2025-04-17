<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rang létrehozása') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash üzenetek --}}
            <x-flash-message />

            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg">
                <div class="p-6 text-white space-y-6">
                    <x-back-button />
                    <h3 class="text-xl font-medium text-white">{{ __('Új rang létrehozása') }}</h3>
                    
                    {{-- Következő ID információ --}}
                    <div class="bg-blue-500/10 text-blue-400 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('A létrehozandó rang azonosítója') }}: <strong>{{ $nextId }}</strong></span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ranks.store') }}" class="rank-form">
                        @csrf
                        {{-- Átadjuk a nextId-t hidden mezőben --}}
                        <input type="hidden" name="next_id" value="{{ $nextId }}">

                        <div class="rank-form-group">
                            <label for="name" class="rank-label">
                                {{ __('Rang neve') }}
                            </label>
                            <input type="text" id="name" name="name" required
                                class="rank-input"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="color" value="{{ __('Szín') }}" />
                            <x-input id="color" type="color" class="mt-1 block w-full" name="color" required />
                        </div>

                        <div class="mb-4">
                            <x-label for="salary_display" value="{{ __('Fizetés') }}" />
                            <x-input 
                                id="salary_display" 
                                type="text" 
                                class="mt-1 block w-full rounded-lg border-gray-700 bg-gray-900 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                value="{{ number_format((float)old('salary_raw', 0), 0, '', ',') }}"
                                required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 8) this.value = this.value.slice(0,8); this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, ','); document.getElementById('salary_raw').value = this.value.replace(/,/g, '');"
                            />
                            <input type="hidden" id="salary_raw" name="salary_raw" value="{{ old('salary_raw', 0) }}" />
                            <p class="text-sm text-gray-400 mt-1">{{ __('Maximum: 10,000,000 Ft') }}</p>
                            @error('salary_raw')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="promotion_days" value="{{ __('Előléptetéshez szükséges napok') }}" />
                            <x-input 
                                id="promotion_days" 
                                type="number" 
                                class="mt-1 block w-full rounded-lg border-gray-700 bg-gray-900 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                name="promotion_days"
                                value="{{ old('promotion_days', 0) }}"
                                min="0"
                                required 
                            />
                            <p class="text-sm text-gray-400 mt-1">{{ __('Minimum: 0 nap') }}</p>
                            @error('promotion_days')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <script>
                            document.getElementById('salary_display').addEventListener('blur', function(e) {
                                if (this.value) {
                                    const numericValue = this.value.replace(/,/g, '');
                                    if (numericValue.length > 8 || parseInt(numericValue) > 10000000) {
                                        this.value = '10,000,000';
                                        document.getElementById('salary_raw').value = '10000000';
                                    } else {
                                        this.value = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                        document.getElementById('salary_raw').value = numericValue;
                                    }
                                }
                            });

                            // Form elküldés előtt
                            document.querySelector('form').addEventListener('submit', function(e) {
                                // Debug
                                console.log('Form elküldése...');
                                console.log('Promotion days érték:', document.getElementById('promotion_days').value);

                                // Fizetés kezelése
                                const salaryDisplay = document.getElementById('salary_display');
                                const rawValue = salaryDisplay.value.replace(/,/g, '');
                                document.getElementById('salary_raw').value = rawValue;

                                // Előléptetési napok kezelése
                                const promotionDaysInput = document.getElementById('promotion_days');
                                if (promotionDaysInput.value === '') {
                                    promotionDaysInput.value = '0';
                                }
                                
                                // Debug
                                console.log('Form adatok elküldés előtt:', {
                                    name: document.getElementById('name').value,
                                    color: document.getElementById('color').value,
                                    salary_display: salaryDisplay.value,
                                    salary_raw: document.getElementById('salary_raw').value,
                                    promotion_days: promotionDaysInput.value
                                });
                            });
                        </script>

                        <div class="flex justify-end space-x-4">
                            {{-- Vissza gomb --}}
                            <a href="{{ route('ranks.index') }}" class="bg-gray-600 hover:bg-gray-500 text-gray-300 hover:text-gray-200 px-4 py-2 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('Vissza') }}
                            </a>

                            {{-- Létrehozás gomb --}}
                            <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Létrehozás') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Color Picker JavaScript --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const colorPicker = document.getElementById('color');
                    const colorPreview = document.getElementById('colorPreview');
                    
                    // Kezdeti érték beállítása
                    colorPreview.style.backgroundColor = colorPicker.value;
                    
                    // Szín változás követése
                    colorPicker.addEventListener('input', function(e) {
                        colorPreview.style.backgroundColor = e.target.value;
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>