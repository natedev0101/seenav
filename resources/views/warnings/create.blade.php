<x-app-layout>
    <div class="py-12 flex justify-center">
        <div class="max-w-4xl w-full sm:px-6 lg:px-8">
            <div class="card p-6">
                <h2 class="text-xl font-semibold text-white mb-4 text-center">{{ __('Új figyelmeztetés hozzáadása') }}</h2>

                <!-- Flash üzenetek -->
                <x-flash-message />

                <form method="POST" action="{{ route('warnings.store') }}" onsubmit="return validateForm()">
                    @csrf

                    <!-- Felhasználó kiválasztása -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-300">{{ __('Felhasználó') }}</label>
                        <select name="user_id" id="user_id" class="custom-input" required>
                            <option value="">{{ __('Kérjük válassz...') }}</option>
                            <!-- Tisztikar (isAdmin) -->
                            @if($users->where('isAdmin', true)->where('is_superadmin', false)->count())
                                <optgroup label="— Tisztikar —">
                                    @foreach($users->where('isAdmin', true)->where('is_superadmin', false)->sortBy('charactername') as $user)
                                        <option value="{{ $user->id }}">
                                            [Leader] {{ $user->charactername }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            <!-- Tagok (ABC sorrendben) -->
                            @if($users->where('isAdmin', false)->where('is_superadmin', false)->count())
                                <optgroup label="— Tagok —">
                                    @foreach($users->where('isAdmin', false)->where('is_superadmin', false)->sortBy('charactername') as $user)
                                        <option value="{{ $user->id }}">{{ $user->charactername }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <!-- Típus kiválasztása -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-300">{{ __('Típus') }}</label>
                        <select name="type" id="type" class="custom-input" required>
                            <option value="">{{ __('Kérjük válassz...') }}</option>
                            <option value="plusz_pont">{{ __('Plusz pont') }}</option>
                            <option value="minusz_pont">{{ __('Mínusz pont') }}</option>
                            <option value="figyelmeztetés">{{ __('Figyelmeztetés') }}</option>
                        </select>
                    </div>

                    <!-- Figyelmeztetés típusa -->
                    <div class="mb-4" id="warning_type_field" style="display: none;">
                        <label for="warning_type" class="block text-sm font-medium text-gray-300">{{ __('Figyelmeztetés típusa') }}</label>
                        <select name="warning_type" id="warning_type" class="custom-input">
                            <option value="">{{ __('Kérjük válassz...') }}</option>
                            <option value="1.">{{ __('1.') }}</option>
                            <option value="2.">{{ __('2.') }}</option>
                            <option value="3.">{{ __('3.') }}</option>
                            <option value="4.">{{ __('4.') }}</option>
                            <option value="5.">{{ __('5.') }}</option>
                        </select>
                    </div>

                    <!-- Indok -->
                    <div class="mb-4">
                        <label for="reason_select" class="block text-sm font-medium text-gray-300">{{ __('Indok') }}</label>
                        <select name="reason_select" id="reason_select" class="custom-input" required>
                            <option value="">{{ __('Kérjük válassz...') }}</option>
                            <option value="indok_1">{{ __('Indok 1') }}</option>
                            <option value="indok_2">{{ __('Indok 2') }}</option>
                            <option value="indok_3">{{ __('Indok 3') }}</option>
                            <option value="indok_4">{{ __('Indok 4') }}</option>
                            <option value="indok_5">{{ __('Indok 5') }}</option>
                            <option value="egyeb">{{ __('Egyéb') }}</option>
                        </select>
                    </div>

                    <div class="mb-4" id="custom_reason_field" style="display: none;">
                        <label for="custom_reason" class="block text-sm font-medium text-gray-300">{{ __('Egyéb indok') }}</label>
                        <input type="text" name="custom_reason" id="custom_reason" class="custom-input">
                    </div>

                    <!-- Pontok kiválasztása -->
                    <div class="mb-4" id="points_field">
                        <label for="points" class="block text-sm font-medium text-gray-300">{{ __('Pontok') }}</label>
                        <select name="points" id="points" class="custom-input">
                            <option value="">{{ __('Kérjük válassz...') }}</option>
                            @for ($i = 1; $i <= 100; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Lejárat dátuma -->
                    <div class="mb-4" id="expires_at_field" style="display: none;">
                        <label for="expires_at" class="block text-sm font-medium text-gray-300">{{ __('Lejárat dátuma') }}</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" class="custom-input">
                        <p id="date-error" class="text-red-500 text-sm mt-1 hidden">{{ __('Nem választható múltbeli dátum!') }}</p>
                    </div>

                    <div class="flex justify-center">
                        <x-primary-button>
                            {{ __('Hozzáadás') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const reasonSelect = document.getElementById('reason_select');
        const customReasonField = document.getElementById('custom_reason_field');
        const typeField = document.getElementById('type');
        const pointsField = document.getElementById('points_field');
        const warningTypeField = document.getElementById('warning_type_field');
        const expiresAtField = document.getElementById('expires_at_field');
        const expiresAtInput = document.getElementById('expires_at');
        const dateError = document.getElementById('date-error');

        reasonSelect.addEventListener('change', function () {
            if (this.value === 'egyeb') {
                customReasonField.style.display = 'block';
            } else {
                customReasonField.style.display = 'none';
            }
        });

        typeField.addEventListener('change', function () {
            if (this.value === 'figyelmeztetés') {
                pointsField.style.display = 'none';
                warningTypeField.style.display = 'block';
                expiresAtField.style.display = 'block';
                expiresAtInput.setAttribute('required', 'required'); // Dátum kötelezővé tétele
            } else {
                pointsField.style.display = 'block';
                warningTypeField.style.display = 'none';
                expiresAtField.style.display = 'none';
                expiresAtInput.removeAttribute('required'); // Dátum nem kötelező
            }
        });

        expiresAtInput.addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                dateError.classList.remove('hidden');
                this.value = '';
            } else {
                dateError.classList.add('hidden');
            }
        });

        reasonSelect.dispatchEvent(new Event('change'));
        typeField.dispatchEvent(new Event('change'));
    </script>
</x-app-layout>