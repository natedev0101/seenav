<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Osztály létrehozása') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash üzenetek --}}
            <x-flash-message />
            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg">
                <div class="p-6 text-white space-y-6">
                    <x-back-button />
                    <h3 class="text-xl font-medium text-white">{{ __('Új osztály létrehozása') }}</h3>
                    
                    {{-- Következő ID információ --}}
                    <div class="bg-blue-500/10 text-blue-400 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('A létrehozandó osztály azonosítója') }}: <strong>{{ $nextId }}</strong></span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('subdivisions.store') }}" class="rank-form">
                        @csrf
                        {{-- Átadjuk a nextId-t hidden mezőben --}}
                        <input type="hidden" name="next_id" value="{{ $nextId }}">

                        <div class="rank-form-group">
                            <label for="name" class="rank-label">
                                {{ __('Osztály neve') }}
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
                            <x-label for="salary" value="{{ __('Fizetés') }}" />
                            <x-input 
                                id="salary_display" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ number_format((float)old('salary', 0), 0, '', ',') }}"
                                required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 8) this.value = this.value.slice(0,8); this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, ','); document.getElementById('salary').value = this.value.replace(/,/g, '');"
                            />
                            <input type="hidden" id="salary" name="salary" value="{{ old('salary', 0) }}" />
                            <p class="text-sm text-gray-400 mt-1">{{ __('Maximum: 10,000,000$') }}</p>
                        </div>
                        
                        <script>
                            document.getElementById('salary_display').addEventListener('blur', function(e) {
                                if (this.value) {
                                    const numericValue = this.value.replace(/,/g, '');
                                    if (numericValue.length > 8 || parseInt(numericValue) > 10000000) {
                                        this.value = '10,000,000';
                                        document.getElementById('salary').value = '10000000';
                                    } else {
                                        this.value = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                        document.getElementById('salary').value = numericValue;
                                    }
                                }
                            });
                        </script>

                        <div class="flex justify-end space-x-4">
                            {{-- Vissza gomb --}}
                            <a href="{{ route('subdivisions.index') }}" class="bg-gray-600 hover:bg-gray-500 text-gray-300 hover:text-gray-200 px-4 py-2 rounded-lg transition-colors flex items-center">
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