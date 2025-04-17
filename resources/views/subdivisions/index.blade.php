<x-app-layout>
    {{-- Flash üzenetek --}}
    <x-flash-message />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Osztályok') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Leader információs doboz --}}
            <div class="leader-info-box">
                <div class="header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    {{ __('Információ') }}
                </div>
                <div class="content">
                    <p>{{ __('Kedves leaderek!') }}</p>
                    <p>{{ __('Az osztályok kezelésénél a törlés gomb megnyomásakor egy ablakban listázásra kerülnek az adott osztályhoz rendelt frakció tagok. Amennyiben törlitek az osztályt, a frakció tagokról is le fog kerülni. Az osztály létrehozása fülön megjelenő ID az új osztály ID-jét mutatja.') }}</p>
                </div>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    {{-- Gombok grid rendszerben --}}
                    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 mb-6">
                        {{-- Új alosztály létrehozása gomb --}}
                        <a href="{{ route('subdivisions.create') }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Új osztály létrehozása') }}
                        </a>
                    </div>

                    {{-- Alosztály lista --}}
                    <div class="space-y-6">
                        <h3 class="text-xl font-medium text-white">{{ __('Osztály lista') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="x-label text-left">{{ __('ID') }}</th>
                                        <th class="x-label text-left">{{ __('Név') }}</th>
                                        <th class="x-label text-left">{{ __('Fizetés (USD)') }}</th>
                                        <th class="x-label text-left">{{ __('Tagok') }}</th>
                                        <th class="x-label text-left">{{ __('Műveletek') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @forelse($subdivisions as $subdivision)
                                        <tr>
                                            <td class="x-cell">{{ $subdivision->id }}</td>
                                            <td class="x-cell" style="color: {{ $subdivision->color }}">{{ $subdivision->name }}</td>
                                            <td class="x-cell">{{ number_format($subdivision->salary, 0, '.', '.') }} $</td>
                                            <td class="x-cell">
                                                <a href="{{ route('subdivisions.users', $subdivision) }}" 
                                                   class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 hover:text-blue-300 transition-colors">
                                                    {{ $subdivision->users_count }} {{ __('Tag') }}
                                                </a>
                                            </td>
                                            <td class="x-cell">
                                                <div class="flex space-x-2">
                                                    {{-- Szerkesztés gomb --}}
                                                    @if(auth()->user()->is_superadmin || auth()->user()->isAdmin)
                                                    <a href="{{ route('subdivisions.edit', $subdivision) }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    {{-- Törlés gomb --}}
                                                    <button type="button" 
                                                            class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors"
                                                            onclick="showDeleteConfirm('{{ $subdivision->id }}', '{{ $subdivision->name }}')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="x-cell text-center">{{ __('Nincs még osztály létrehozva.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Törlés megerősítő modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-900/50 items-center justify-center hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-lg mx-auto mt-20 relative">
            <h3 class="text-xl font-medium text-white mb-4">{{ __('Osztály törlése') }}</h3>
            <div id="deleteModalContent" class="space-y-4">
                <p class="text-gray-300">{{ __('Biztosan törölni szeretnéd ezt az osztályt?') }}</p>
                <div id="usersWithSubdivision" class="bg-gray-700/50 p-4 rounded-lg max-h-48 overflow-y-auto">
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">{{ __('Felhasználók ebben az alosztályban:') }}</p>
                    <ul id="usersList" class="space-y-2 text-gray-300"></ul>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-4 py-2 rounded-lg transition-colors"
                            onclick="hideDeleteModal()">
                        {{ __('Mégsem') }}
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 px-4 py-2 rounded-lg transition-colors">
                            {{ __('Törlés') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript a törlés kezeléséhez --}}
    <script>
        async function showDeleteConfirm(subdivisionId, subdivisionName) {
            // Modal megjelenítése
            const modal = document.getElementById('deleteModal');
            const usersList = document.getElementById('usersList');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Form action beállítása
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/alosztalyok/${subdivisionId}`;

            // Felhasználók lekérése
            try {
                const response = await fetch(`/alosztalyok/${subdivisionId}/users-json`);
                const data = await response.json();
                
                usersList.innerHTML = ''; // Lista ürítése
                
                if (data.users.length === 0) {
                    usersList.innerHTML = `<li class="text-gray-400">{{ __('Nincs felhasználó ebben az alosztályban.') }}</li>`;
                } else {
                    data.users.forEach(user => {
                        usersList.innerHTML += `
                            <li class="flex items-center">
                                <span class="w-4 h-4 mr-2 rounded-full ${user.is_online ? 'bg-green-500' : 'bg-gray-500'}"></span>
                                ${user.charactername}
                            </li>`;
                    });
                }
            } catch (error) {
                console.error('Hiba:', error);
                usersList.innerHTML = `<li class="text-red-400">{{ __('Hiba történt a felhasználók betöltése közben.') }}</li>`;
            }
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // ESC gombra modal bezárása
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });

        // Modal kívül kattintásra bezárás
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });
    </script>
</x-app-layout>