<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-900 leading-tight">
        {{ __('Online Felhasználók') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Fejléc -->
        <div class="bg-gray-100 shadow sm:rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 text-center">
                {{ __('Összes online felhasználó') }} ({{ $onlineUsers->count() }})
            </h2>

            <!-- Felhasználók listája -->
            <ul class="space-y-4">
                @foreach($onlineUsers as $user)
                    <li class="flex items-center gap-4">
                        <!-- Profilkép -->
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-green-500">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('img/default-profile.png') }}" 
                                 alt="Profilkép" 
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                        <!-- Felhasználó neve -->
                        <p class="text-md text-gray-900">
                            {{ $user->charactername }}
                        </p>
                    </li>
                @endforeach
            </ul>

            <!-- Vissza gomb -->
            <div class="text-center mt-6">
                <a href="{{ route('dashboard') }}">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        {{ __('Vissza a főoldalra') }}
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>