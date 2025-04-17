<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Szolgálat kezelés') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Szolgálat vezérlő kártya -->
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg flex items-center justify-center">
                @if(!$isOnDuty)
                    <button onclick="startService()" class="px-8 py-4 rounded-lg transition-colors flex items-center space-x-3 text-lg font-medium bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Szolgálatba lépés</span>
                    </button>
                @else
                    <button onclick="endService()" class="px-8 py-4 rounded-lg transition-colors flex items-center space-x-3 text-lg font-medium bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                        <span>Kilépés a szolgálatból</span>
                    </button>
                @endif
            </div>

            <!-- Statisztikák -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Heti szolgálati idő -->
                <div class="bg-gray-800 shadow-md p-6 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-500/10 text-blue-400 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-2xl font-semibold text-white">{{ $weeklyDuration }} perc</span>
                            <span class="text-sm text-gray-400">Heti szolgálati idő</span>
                        </div>
                    </div>
                </div>

                <!-- Összes szolgálati idő -->
                <div class="bg-gray-800 shadow-md p-6 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-500/10 text-blue-400 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-2xl font-semibold text-white">{{ $totalServiceTime }} perc</span>
                            <span class="text-sm text-gray-400">Összes szolgálati idő</span>
                        </div>
                    </div>
                </div>

                <!-- Szolgálat kezdete -->
                @if($serviceStart)
                <div class="bg-gray-800 shadow-md p-6 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-500/10 text-blue-400 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-2xl font-semibold text-white">{{ \Carbon\Carbon::parse($serviceStart)->format('H:i') }}</span>
                            <span class="text-sm text-gray-400">Szolgálat kezdete</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Aktív szolgálatban lévők listája -->
            <div class="flex flex-col space-y-4">
                @if($activeUsers->count() > 0)
                    <table class="min-w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-2">
                                    Karakter
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    Kezdés időpontja
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    Eltelt idő
                                </th>
                                @if($isAdmin || $isOfficer)
                                    <th scope="col" class="px-6 py-2">
                                        Rang
                                    </th>
                                    <th scope="col" class="px-6 py-2">
                                        Alosztály
                                    </th>
                                    <th scope="col" class="px-6 py-2">
                                        Műveletek
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeUsers as $activeUser)
                                <tr class="border-b bg-gray-800 border-gray-700 hover:bg-gray-700">
                                    <td class="px-6 py-2">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('users.show', $activeUser->id) }}" class="relative flex-shrink-0 hover:opacity-75 transition-opacity">
                                                <img src="{{ $activeUser->profile_picture ? '/storage/' . $activeUser->profile_picture : '/images/default-profile.png' }}" 
                                                     alt="Profilkép" 
                                                     class="w-16 h-16 rounded-lg object-cover object-center">
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-gray-800 {{ $activeUser->is_on_duty ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                                            </a>
                                            <div class="font-medium text-white">
                                                <a href="{{ route('users.show', $activeUser->id) }}" class="hover:text-blue-400 transition-colors">
                                                    {{ $activeUser->charactername ?? 'N/A' }}
                                                </a>
                                                @if($isAdmin)
                                                    <div class="text-xs text-gray-400">{{ $activeUser->username }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-2">
                                        {{ $activeUser->activeDuty ? \Carbon\Carbon::parse($activeUser->activeDuty->started_at)->format('H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-2">
                                        {{ $activeUser->activeDuty ? \Carbon\Carbon::parse($activeUser->activeDuty->started_at)->diffForHumans() : '' }}
                                    </td>
                                    @if($isAdmin || $isOfficer)
                                        <td class="px-6 py-2">
                                            {{ optional($activeUser->rank)->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-2">
                                            @foreach($userSubdivisions[$activeUser->id] ?? [] as $subdivision)
                                                <span class="inline-flex items-center px-2 py-1 mr-1 text-xs font-medium bg-blue-500/10 text-blue-400 rounded">
                                                    {{ $subdivision->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-2">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="window.location.href='{{ route('users.show', $activeUser->id) }}'" 
                                                        class="px-3 py-1 bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 rounded transition-colors">
                                                    Profil
                                                </button>
                                                @if($isAdmin || $isOfficer)
                                                    <button onclick="showForceEndModal({{ $activeUser->id }}, '{{ $activeUser->charactername }}')"
                                                            class="px-3 py-1 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded transition-colors">
                                                        Szolgálat leállítása
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center text-gray-400 py-4">
                        Jelenleg nincs aktív szolgálatban lévő felhasználó.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Force End Modal -->
    <div id="forceEndModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-white mb-4">Szolgálat kényszerített leállítása</h3>
            <p class="text-gray-400 mb-4">
                <span id="forceEndUserName"></span> szolgálatának leállítása
            </p>
            <div class="mb-4">
                <label for="forceEndReason" class="block text-sm font-medium text-gray-400 mb-2">Indoklás</label>
                <textarea id="forceEndReason" 
                          class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          rows="3"
                          placeholder="Add meg a leállítás okát..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeForceEndModal()"
                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 transition-colors">
                    Mégse
                </button>
                <button onclick="submitForceEnd()"
                        class="px-4 py-2 bg-red-500/20 text-red-400 rounded hover:bg-red-500/30 transition-colors">
                    Leállítás
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function startService() {
            axios.post('{{ route("duty.start") }}')
                .then(response => {
                    if (response.data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Hiba történt:', error);
                });
        }

        function endService() {
            axios.post('{{ route("duty.end") }}')
                .then(response => {
                    if (response.data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Hiba történt:', error);
                });
        }

        let currentUserId = null;

        function showForceEndModal(userId, characterName) {
            currentUserId = userId;
            document.getElementById('forceEndUserName').textContent = characterName;
            document.getElementById('forceEndReason').value = '';
            document.getElementById('forceEndModal').classList.remove('hidden');
            document.getElementById('forceEndModal').classList.add('flex');
        }

        function closeForceEndModal() {
            document.getElementById('forceEndModal').classList.add('hidden');
            document.getElementById('forceEndModal').classList.remove('flex');
            currentUserId = null;
        }

        function submitForceEnd() {
            const reason = document.getElementById('forceEndReason').value.trim();
            
            if (!reason) {
                alert('Kérlek add meg a leállítás okát!');
                return;
            }

            fetch(`/duty/${currentUserId}/force-end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.error || 'Hiba történt a szolgálat leállítása közben!');
                }
            })
            .catch(error => {
                console.error('Hiba:', error);
                alert('Hiba történt a szolgálat leállítása közben!');
            })
            .finally(() => {
                closeForceEndModal();
            });
        }
    </script>
    @endpush
</x-app-layout>
