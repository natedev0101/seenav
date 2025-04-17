<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow sm:rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-6">{{ __('Felhasználók aktivitási ideje') }}</h2>

                <table class="w-full text-gray-300">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="py-2 px-4">{{ __('ID') }}</th>
                            <th class="py-2 px-4">{{ __('Felhasználónév') }}</th>
                            <th class="py-2 px-4">{{ __('Karakter név') }}</th>
                            <th class="py-2 px-4">{{ __('Eltöltött idő (óra:perc)') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-600">
                                <td class="py-2 px-4">{{ $user->id }}</td>
                                <td class="py-2 px-4">{{ $user->username }}</td>
                                <td class="py-2 px-4">{{ $user->charactername }}</td>
                                <td class="py-2 px-4">{{ $user->formatted_time_spent }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>