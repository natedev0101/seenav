<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Osztály szerkesztése:') }} <span style="color: {{ $subdivision->color }}">{{ $subdivision->name }}</span>
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
            <!-- Fejléc kártya -->
            <div class="bg-gray-800 overflow-hidden shadow-md rounded-lg">
                <div class="p-6 text-white space-y-6">
                    <x-back-button />
                    <h3 class="text-xl font-medium text-white">{{ __('Osztály szerkesztése') }}</h3>

            <!-- Űrlap kártya -->

                <form method="POST" action="{{ route('subdivisions.update', $subdivision) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Név -->
                    <div class="mb-4">
                        <x-label for="name" value="{{ __('Név') }}" />
                        <x-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name', $subdivision->name)" required autofocus />
                    </div>

                        <!-- Osztály színe -->
                        <div class="mb-4">
                            <x-label for="color" value="{{ __('Szín') }}" />
                            <x-input id="color" type="color" class="mt-1 block w-full" name="color" value="{{ old('color', $subdivision->color) }}" required />
                        </div>

                        <div class="mb-4">
                            <x-label for="salary" value="{{ __('Fizetés') }}" />
                            <x-input 
                                id="salary_display" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ number_format((float)old('salary', $subdivision->salary), 0, '', ',') }}"
                                required 
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 8) this.value = this.value.slice(0,8); this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, ','); document.getElementById('salary').value = this.value.replace(/,/g, '');"
                            />
                            <input type="hidden" id="salary" name="salary" value="{{ old('salary', $subdivision->salary) }}" />
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

                    <!-- Gombok -->
                    <div class="flex justify-end space-x-3">
                        <button type="submit" 
                                class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors inline-flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ __('Mentés') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color_text').value = e.target.value;
        });
    </script>
</x-app-layout>