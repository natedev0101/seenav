<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Áttekintés') }}
        </h2>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    @csrf
    <div x-data="{ 
        init() {
            console.log('Dashboard init');
        }
    }" class="min-h-screen bg-gray-900 body">
        <div class="p-4 sm:p-6">
            <!-- Profil kártya -->
            <div class="max-w-7xl mx-auto mb-6">
                <div class="bg-gray-800 shadow-md p-3 sm:p-4 rounded-lg">
                    <!-- Mobil nézet (oszlopos elrendezés) és desktop nézet (soros elrendezés) -->
                    <div class="flex flex-row items-center gap-4">
                        <!-- Profilkép és online státusz -->
                        <div class="relative flex-shrink-0">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-profile.png') }}" 
                                alt="Profilkép" 
                                class="w-16 h-16 rounded-lg object-cover object-center">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-gray-800 {{ Auth::user()->is_online ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                        </div>
                        
                        <div class="flex-grow">
                            <!-- Név és online státusz -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-xl font-bold text-white">
                                        {{ Auth::user()->charactername }}
                                    </h2>
                                    <!-- Gombok -->
                                    <div class="flex items-center gap-1.5">
                                        <!-- Névváltás gomb -->
                                        <a href="{{ route('name-change.request') }}" 
                                           class="group relative bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors" 
                                           title="Névváltás kérelmezése"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            
                                            <!-- Tooltip -->
                                            <div class="absolute left-1/2 -translate-x-1/2 -bottom-8 hidden group-hover:block bg-gray-900 text-xs text-gray-300 px-2 py-1 rounded whitespace-nowrap z-50">
                                                Névváltás kérelmezése
                                            </div>
                                        </a>

                                        @if(Auth::user()->isAdmin || Auth::user()->is_superadmin)
                                            <div class="relative">
                                                <div class="group bg-{{ Auth::user()->two_factor_required ? 'green' : 'red' }}-500/10 hover:bg-{{ Auth::user()->two_factor_required ? 'green' : 'red' }}-500/20 text-{{ Auth::user()->two_factor_required ? 'green' : 'red' }}-400 hover:text-{{ Auth::user()->two_factor_required ? 'green' : 'red' }}-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    
                                                    <!-- Tooltip -->
                                                    <div class="absolute left-1/2 -translate-x-1/2 -bottom-8 hidden group-hover:block bg-gray-900 text-xs text-gray-300 px-2 py-1 rounded whitespace-nowrap z-50">
                                                        Kétfaktoros hitelesítés {{ Auth::user()->two_factor_required ? 'bekapcsolva' : 'kikapcsolva' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Fizetés gomb -->
                                        <button 
                                            onclick="openSalaryModal()"
                                            class="group relative bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors"
                                            title="Fizetési információk"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            
                                            <!-- Tooltip -->
                                            <div class="absolute left-1/2 -translate-x-1/2 -bottom-8 hidden group-hover:block bg-gray-900 text-xs text-gray-300 px-2 py-1 rounded whitespace-nowrap z-50">
                                                Fizetési információk megtekintése
                                            </div>
                                        </button>

                                        <!-- Hírek gomb -->
                                        <a 
                                            href="{{ route('news.index') }}"
                                            class="relative"
                                            title="Új hírek!"
                                        >
                                            <!-- Új hír jelző pötty -->
                                            @if(Auth::user()->unreadNewsCount() > 0)
                                                <div class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">
                                                    {{ Auth::user()->unreadNewsCount() }}
                                                </div>
                                            @endif
                                            
                                            <button type="button" class="group relative bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v6a2 2 0 01-2 2z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 6H8v4h8V6z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14H8"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 18H8"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Tooltip -->
                                            <div class="absolute left-1/2 -translate-x-1/2 -bottom-8 hidden group-hover:block bg-gray-900 text-xs text-gray-300 px-2 py-1 rounded whitespace-nowrap z-50">
                                                Új hírek érkeztek!
                                            </div>
                                        </a>
                                    </div>

                                    @if(Auth::user()->hasActiveNameChangeRequest())
                                        <span class="bg-yellow-500/10 text-yellow-400 px-2 py-0.5 rounded text-xs">
                                            Folyamatban lévő kérelem
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm {{ Auth::user()->is_online ? 'text-green-400' : 'text-gray-400' }}">
                                    {{ Auth::user()->is_online ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                            
                            <!-- Rangok és alosztályok -->
                            <div class="flex flex-wrap gap-2 mt-2">
                                <!-- Admin/Superadmin badge -->
                                @if(Auth::user()->is_superadmin)
                                    <span class="bg-purple-500/10 text-purple-400 px-2 py-1 rounded-lg text-sm whitespace-nowrap">
                                        Webmester
                                    </span>
                                @elseif(Auth::user()->isAdmin)
                                    <span class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-lg text-sm whitespace-nowrap">
                                        Leader
                                    </span>
                                @endif

                                <!-- Rang -->
                                @if(Auth::user()->rank)
                                    <span class="px-2 py-1 rounded-lg text-sm whitespace-nowrap" 
                                          style="background-color: {{ Auth::user()->rank->color }}; color: #ffffff; text-shadow: -1px -1px 0 black,  
                          1px -1px 0 black,
                          -1px 1px 0 black, 
                          1px 1px 0 black;"">
                                        {{ Auth::user()->rank->name }}
                                    </span>
                                @else
                                    <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-lg text-sm whitespace-nowrap">
                                        Nincs rang
                                    </span>
                                @endif

                                <!-- Alosztályok -->
                                @if(Auth::user()->subdivisions->count() > 0)
                                    @foreach(Auth::user()->subdivisions as $subdivision)
                                        <span class="px-2 py-1 rounded-lg text-sm whitespace-nowrap" 
                                              style="background-color: {{ $subdivision->color }}; color: #ffffff; text-shadow: -1px -1px 0 black,  
                          1px -1px 0 black,
                          -1px 1px 0 black, 
                          1px 1px 0 black;"">
                                            {{ $subdivision->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-lg text-sm whitespace-nowrap">
                                        Nincs alosztály
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Felhasználói adatok kártya -->
            <div class="max-w-7xl mx-auto mb-6">
                <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-700 flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"></path>
                        </svg>
                        <h2 class="text-lg font-medium text-white">Felhasználói adatok</h2>
                    </div>
                    <div class="p-4 space-y-6">
                        <!-- Azonosítók szekció -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Azonosítók</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Felhasználónév -->
                                <div class="bg-gray-700/30 p-3 rounded-lg flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-400">Felhasználónév</div>
                                        <div class="text-white font-medium">{{ Auth::user()->username }}</div>
                                    </div>
                                </div>
                                <!-- IngameNév -->
                                <div class="bg-gray-700/30 p-3 rounded-lg flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-400">IngameNév</div>
                                        <div class="text-white font-medium">{{ Auth::user()->charactername }}</div>
                                    </div>
                                </div>
                                <!-- Karakter ID -->
                                <div class="bg-gray-700/30 p-3 rounded-lg flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-400">Karakter ID</div>
                                        <div class="text-white font-medium">#{{ Auth::user()->id }}</div>
                                    </div>
                                </div>
                                <!-- UCP -->
                                <div class="bg-gray-700/30 p-3 rounded-lg flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m11.142 0l-5.571 3-5.571-3"></path>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-400">UCP</div>
                                        @if(Auth::user()->character_id)
                                    <a href="{{ Auth::user()->character_id }}" 
                                       class="block text-blue-400 hover:text-blue-300 mt-1 transition-colors text-lg">
                                        UCP profil megtekintése
                                    </a>
                                @else
                                    <p class="text-lg text-gray-500 mt-1">Nincs elérhető információ</p>
                                @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rang információk és dátumok -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Rang információk</h3>
                            <div class="bg-gray-700/30 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-400">Jelenlegi rendfokozat</div>
                                            @if(Auth::user()->rank)
                                                <span class="px-3 py-1 rounded-lg text-base mt-1 inline-block" 
                                                      style="background-color: {{ Auth::user()->rank->color }}20; color: {{ Auth::user()->rank->color }};">
                                                    {{ Auth::user()->rank->name }}
                                                </span>
                                            @else
                                                <span class="bg-red-500/10 text-red-400 px-3 py-1 rounded-lg text-base mt-1 inline-block">
                                                    Nincs rang
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-400">Utolsó előléptetés</div>
                                        @php
                                            $lastRankChange = App\Models\RankChange::where('user_id', $user->id)
                                                ->with(['oldRank', 'newRank'])
                                                ->latest()
                                                ->first();
                                        @endphp
                                        <div class="text-gray-300 mt-1">
                                            @if($lastRankChange)
                                                {{ $lastRankChange->created_at->format('Y.m.d') }}
                                                <div class="text-sm text-gray-400">
                                                    {{ $lastRankChange->oldRank ? $lastRankChange->oldRank->name : 'Nincs rang' }}
                                                    →
                                                    {{ $lastRankChange->newRank ? $lastRankChange->newRank->name : 'Nincs rang' }}
                                                </div>
                                            @else
                                                Nincs információ
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Egyéb dátumok -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Felvétel dátuma -->
                            <div class="bg-gray-700/30 p-3 rounded-lg flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <div class="text-sm text-gray-400">Felvétel dátuma</div>
                                    <div class="text-gray-300">{{ Auth::user()->created_at->format('Y-m-d H:i:s') }}</div>
                                </div>
                            </div>
                            <!-- Névváltás időpontja -->
                            <div class="bg-gray-700/30 p-3 rounded-lg flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                </svg>
                                <div>
                                    <div class="text-sm text-gray-400">Névváltás időpontja</div>
                                    @php
$lastNameChange = auth()->user()->nameChangeRequests()
    ->where('status', 'approved')
    ->latest('processed_at')
    ->first();
@endphp

@if($lastNameChange && $lastNameChange->processed_at)
    <span class="text-gray-300">{{ $lastNameChange->processed_at->format('Y-m-d H:i:s') }}</span>
@else
    <span class="text-gray-400">Nincs korábbi névváltoztatás</span>
@endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Online Users Card -->
            <div class="bg-gray-800/95 shadow-lg rounded-lg p-3 backdrop-blur-sm relative overflow-hidden">
                <!-- Háttér gradiens -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-purple-500/5 to-blue-500/5 opacity-50"></div>
                
                <!-- Tartalom -->
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-blue-500/10">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-300 uppercase tracking-wider">Online felhasználók</h3>
                        </div>
                        <span class="text-xs px-2 py-1 bg-blue-500/10 text-blue-400 rounded-lg font-medium">{{ count($onlineUsers->where('is_online', true)) }} online</span>
                    </div>
                    
                    <!-- Users List Container -->
                    <div x-data="{ currentPage: 1, itemsPerPage: 4 }" class="space-y-2">
                        <!-- Users Grid -->
                        <div class="grid gap-1.5">
                            @foreach($onlineUsers->where('is_online', true) as $index => $user)
                                <div x-show="(currentPage - 1) * itemsPerPage <= {{ $index }} && {{ $index }} < currentPage * itemsPerPage"
                                     class="group">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="flex items-center space-x-3 p-2 rounded-lg bg-gray-700/20 hover:bg-gray-700/40 transition-all duration-200">
                                        <!-- User Avatar -->
                                        <div class="relative flex-shrink-0">
                                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}"
                                                 alt="{{ $user->charactername }}" 
                                                 class="h-9 w-9 rounded-lg ring-2 ring-gray-700/50 group-hover:ring-blue-500/50 transition-all duration-300 object-cover object-center">
                                            <!-- Online jelzés -->
                                            <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-gray-800 {{ $user->is_online ? 'bg-green-500' : 'bg-gray-500' }}"></div>
                                        </div>
                                        
                                        <!-- User Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium truncate {{ $user->is_superadmin ? 'bg-gradient-to-r from-purple-400 via-blue-400 to-purple-400 text-transparent bg-clip-text' : 'text-white' }} group-hover:text-blue-400 transition-colors duration-200">
                                                    {{ $user->charactername }}
                                                </p>
                                                <div class="h-3 w-px bg-gray-700"></div>
                                                <span class="text-xs {{ $user->is_superadmin ? 'text-purple-400 font-medium' : 'text-gray-500' }} truncate px-1.5 py-0.5 {{ $user->is_superadmin ? 'bg-purple-500/5 rounded-md' : '' }}">
                                                    @if(Auth::user()->isAdmin || Auth::user()->is_superadmin)
                                                        ({{ $user->username }})
                                                    @endif
                                                </span>
                                                
                                                <!-- Rang jelzések -->
                                                @if($user->is_superadmin)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-medium bg-purple-500/10 text-purple-400 group-hover:bg-purple-500/20 transition-colors duration-200">
                                                        Webmester
                                                    </span>
                                                @elseif($user->isAdmin)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-medium bg-blue-500/10 text-blue-400 group-hover:bg-blue-500/20 transition-colors duration-200">
                                                        Leader
                                                    </span>
                                                @elseif($user->is_officer)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-medium bg-green-500/10 text-green-400 group-hover:bg-green-500/20 transition-colors duration-200">
                                                        Tiszt
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <!-- Rang buborék -->
                                                @if($user->rank)
                                                    <span class="px-2 py-0.5 rounded-lg text-xs font-medium whitespace-nowrap" 
                                                          style="background-color: {{ $user->rank->color }}20; color: {{ $user->rank->color }};">
                                                        {{ $user->rank->name }}
                                                    </span>
                                                @endif
                                                
                                                <!-- Alosztály buborék -->
                                                @if($user->subdivisions && $user->subdivisions->count() > 0)
                                                    @foreach($user->subdivisions as $subdivision)
                                                        <span class="px-2 py-0.5 rounded-lg text-xs font-medium whitespace-nowrap" 
                                                              style="background-color: {{ $subdivision->color }}20; color: {{ $subdivision->color }};">
                                                            {{ $subdivision->name }}
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination Controls -->
                        @if(count($onlineUsers->where('is_online', true)) > 4)
                            <div class="flex justify-between items-center pt-2 mt-2 border-t border-gray-700/30">
                                <button @click="if(currentPage > 1) currentPage--"
                                        :class="{'opacity-30 cursor-not-allowed': currentPage === 1}"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-300 hover:bg-gray-700/30 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <span class="text-xs text-gray-500 select-none font-medium">
                                    <span x-text="currentPage"></span> / <span x-text="Math.ceil({{ count($onlineUsers->where('is_online', true)) }} / itemsPerPage)"></span>
                                </span>
                                <button @click="if(currentPage < Math.ceil({{ count($onlineUsers->where('is_online', true)) }} / itemsPerPage)) currentPage++"
                                        :class="{'opacity-30 cursor-not-allowed': currentPage >= Math.ceil({{ count($onlineUsers->where('is_online', true)) }} / itemsPerPage)}"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-300 hover:bg-gray-700/30 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            

    <!-- Fizetési információk modal -->
    <div id="salaryModal" class="fixed inset-0 bg-gray-900/75 z-50 hidden">
        <div class="flex min-h-screen items-center justify-center p-4">
            <!-- Modal panel -->
            <div class="w-full max-w-4xl rounded-lg bg-gray-800 shadow-xl">
                <!-- Modal header -->
                <div class="border-b border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white">Fizetési információk</h2>
                        <button type="button" class="text-gray-400 hover:text-gray-300" onclick="closeSalaryModal()">
                            <span class="sr-only">Bezárás</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bal oldal -->
                        <div class="space-y-6">
                            <!-- Rang fizetés -->
                            <div class="bg-gray-700/30 p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-medium text-white">Rang</h3>
                                    <span class="px-3 py-1 text-sm rounded-full bg-blue-500/20 text-blue-400" id="rankName">Betöltés...</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-300">
                                    <span>Fizetés:</span>
                                    <span class="font-medium text-green-400" id="rankSalary">Betöltés...</span>
                                </div>
                            </div>
                            
                            <!-- Alosztály fizetések -->
                            <div class="bg-gray-700/30 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-white mb-3">Alosztályok</h3>
                                <div id="subdivisionSalaries" class="space-y-2 text-sm">
                                    <p class="text-gray-300">Betöltés...</p>
                                </div>
                            </div>

                            <!-- Aktuális jelentés díj -->
                            <div class="bg-gray-700/30 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-white mb-3">Jelentések</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between text-gray-300">
                                        <span>Egy jelentés díja:</span>
                                        <span class="font-medium text-green-400" id="currentReportSalary">Betöltés...</span>
                                    </div>
                                    <div class="flex items-center justify-between text-gray-300">
                                        <span>Elfogadott jelentések:</span>
                                        <span class="font-medium text-blue-400" id="acceptedReportsCount">Betöltés...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jobb oldal -->
                        <div class="space-y-6">
                            <!-- Bónuszok -->
                            <div class="bg-gray-700/30 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-white mb-3">Bónuszok</h3>
                                <div id="bonusList" class="space-y-2 text-sm">
                                    <p class="text-gray-300">Betöltés...</p>
                                </div>
                            </div>

                            <!-- Teljes összeg -->
                            <div class="bg-green-500/10 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-white">Teljes fizetés</h3>
                                        <p class="text-sm text-gray-400">Minden bónusszal együtt</p>
                                    </div>
                                    <span class="text-2xl font-bold text-green-400" id="totalSalary">Betöltés...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fizetési modal kezelése
        function openSalaryModal() {
            document.getElementById('salaryModal').classList.remove('hidden');
            // Fizetési információk lekérése
            fetch('/api/salary-info')
                .then(response => response.json())
                .then(data => {
                    console.log('Fizetési adatok betöltve:', data);
                    
                    // Rang fizetés
                    document.getElementById('rankName').textContent = data.rank_salary.name;
                    document.getElementById('rankSalary').textContent = `$${new Intl.NumberFormat('en-US').format(data.rank_salary.amount)}`;
                    
                    // Alosztály fizetések
                    const subdivisionSalariesHtml = data.subdivision_salaries.length > 0 
                        ? data.subdivision_salaries.map(sub => `
                            <div class="flex items-center justify-between bg-gray-600/20 p-2 rounded">
                                <span class="text-blue-400">${sub.name}</span>
                                <span class="font-medium text-green-400">$${new Intl.NumberFormat('en-US').format(sub.salary)}</span>
                            </div>
                        `).join('')
                        : '<p class="text-gray-500 italic">Nincs alosztály fizetés</p>';
                    document.getElementById('subdivisionSalaries').innerHTML = subdivisionSalariesHtml;
                    
                    // Aktuális jelentés díj megjelenítése
                    document.getElementById('currentReportSalary').textContent = `$${new Intl.NumberFormat('en-US').format(data.current_report_salary)}`;
                    document.getElementById('acceptedReportsCount').textContent = `${data.accepted_reports_count} db`;
                    
                    // Bónuszok listázása
                    const bonusesHtml = data.bonuses.length > 0
                        ? data.bonuses.map(bonus => `
                            <div class="flex items-center justify-between bg-gray-600/20 p-2 rounded">
                                <span class="text-gray-300">${bonus.name}</span>
                                <span class="font-medium text-green-400">$${new Intl.NumberFormat('en-US').format(bonus.amount)}</span>
                            </div>
                        `).join('')
                        : '<p class="text-gray-500 italic">Nincsenek bónuszok</p>';
                    document.getElementById('bonusList').innerHTML = bonusesHtml;

                    // Teljes fizetés
                    document.getElementById('totalSalary').textContent = `$${new Intl.NumberFormat('en-US').format(data.total_salary)}`;
                })
                .catch(error => {
                    console.error('Hiba történt:', error);
                    // Hiba esetén értesítjük a felhasználót
                    document.getElementById('bonusList').innerHTML = '<p class="text-red-400">Hiba történt az adatok betöltése közben</p>';
                });
        }

        function closeSalaryModal() {
            document.getElementById('salaryModal').classList.add('hidden');
        }

        // ESC gomb kezelése a modal bezárásához
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSalaryModal();
            }
        });

        // Modal háttérre kattintás kezelése
        document.getElementById('salaryModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeSalaryModal();
            }
        });
    </script>

</x-app-layout>
