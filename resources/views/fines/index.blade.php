<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Bírságok kezelése') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="fines-container">

                <!-- Új bírság hozzáadása -->
                <h3 class="text-lg font-bold text-white mb-3">Új bírság hozzáadása</h3>
                <form action="{{ route('fines.store') }}" method="POST" class="fines-form">
                    @csrf
                    <div>
                        <label class="block text-sm text-white">Bírság indoka:</label>
                        <input type="text" name="reason" class="fines-input" required>
                    </div>

                    <div>
                        <label class="block text-sm text-white">Összeg ($):</label>
                        <input type="number" name="amount" class="fines-input" required min="1">
                    </div>

                    <div class="col-span-2 md:col-span-4 flex justify-end">
                        <button type="submit" class="fines-button">{{ __('Hozzáadás') }}</button>
                    </div>
                </form>

                <!-- Bírságok listája -->
                <div class="mt-6">
                    <h3 class="text-xl font-bold text-white mb-3">Elérhető bírságok</h3>
                    <table class="fines-table">
                        <thead>
                            <tr>
                                <th>Indok</th>
                                <th>Összeg</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fines as $fine)
                                <tr>
                                    <td>
                                        <form action="{{ route('fines.update', $fine->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="reason" value="{{ $fine->reason }}" class="fines-input">
                                    </td>
                                    <td>
                                            <input type="number" name="amount" value="{{ $fine->amount }}" class="fines-input" min="1">
                                    </td>
                                    <td>
                                            <button type="submit" class="fines-button">{{ __('Mentés') }}</button>
                                        </form>

                                        <form action="{{ route('fines.destroy', $fine->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="fines-danger-button" onclick="return confirm('Biztos törölni szeretnéd?')">
                                                {{ __('Törlés') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>