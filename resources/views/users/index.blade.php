<x-app-layout>
    <!-- CSS importálása -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <x-slot name="headerIcon">
        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-white leading-tight">
            {{ __('Felhasználók listája') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-3">
        <!-- Információs blokk -->
        <div class="glass-effect rounded-lg shadow p-3 mb-3">
            <div class="flex items-center gap-3">
                <div class="floating-animation">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-white">{{ __('Felhasználók Kezelése') }}</h1>
                    <p class="text-xs text-gray-300">{{ __('Itt találod a frakció tagjait. Kattints a felhasználók kártyájára a profiljuk megtekintéséhez.') }}</p>
                </div>
            </div>
        </div>

        <!-- Kereső és szűrés -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
            <div class="search-input-container">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Keresés név, rang alapján.." 
                        class="search-input w-full pl-12 pr-4 py-3 rounded-xl transition-all"
                        onkeyup="filterUsers()"
                    >
                </div>
            </div>
            
            <div class="search-input-container">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <select 
                        id="roleFilter" 
                        class="search-select w-full pl-12 pr-10 py-3 rounded-xl appearance-none transition-all"
                    >
                        <option value="all">Összes felhasználó</option>
                        <option value="webmaster">Webmesterek</option>
                        <option value="leader">Leaderek</option>
                        <option value="officer">Tisztek</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Webmesterek -->
        @if($fixedUsers->where('is_superadmin', true)->count())
            <div class="section-header webmaster mb-3">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-white">Webmesterek</h2>
                </div>
            </div>
            <div id="fixedWebmasters" class="grid gap-3 mb-3">
                @foreach ($fixedUsers->where('is_superadmin', true) as $user)
                    <x-user-card :user="$user" role="webmaster"/>
                @endforeach
            </div>
        @endif

        <!-- Leaderek -->
        @if($fixedUsers->where('isAdmin', true)->where('is_superadmin', false)->count())
            <div class="section-header leader mb-3">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-white">Leaderek</h2>
                </div>
            </div>
            <div id="fixedLeaders" class="grid gap-3 mb-3">
                @foreach ($fixedUsers->where('isAdmin', true)->where('is_superadmin', false) as $user)
                    <x-user-card :user="$user" role="leader"/>
                @endforeach
            </div>
        @endif

        <!-- Tisztek -->
        @if($fixedUsers->where('is_officer', true)->where('is_superadmin', false)->where('isAdmin', false)->count())
            <div class="section-header officer mb-3">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-white">Tisztek</h2>
                </div>
            </div>
            <div id="officers" class="grid gap-3 mb-3">
                @foreach ($fixedUsers->where('is_officer', true)->where('is_superadmin', false)->where('isAdmin', false) as $user)
                    <x-user-card :user="$user" role="officer"/>
                @endforeach
            </div>
        @endif

        <!-- Egyéb felhasználók -->
        <div class="section-header users mb-3">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h2 class="text-lg font-bold text-white">Tagok</h2>
            </div>
        </div>
        <div id="userList" class="grid gap-3">
            @foreach ($pagedUsers as $user)
                <x-user-card :user="$user" role="others"/>
            @endforeach
        </div>

        <!-- Infinite Scroll Loader -->
        <div id="loading" class="flex items-center justify-center text-gray-400 mt-3 hidden">
            <div class="glass-effect rounded-lg px-6 py-3 flex items-center space-x-3">
                <svg class="animate-spin h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>További felhasználók betöltése...</span>
            </div>
        </div>
    </div>

    <!-- Realtime Kereső és Szűrés -->
    <script>
        function filterUsers() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const selectedRole = document.getElementById('roleFilter').value;
            const userCards = document.querySelectorAll('.user-card');

            userCards.forEach((card) => {
                const userName = card.textContent.toLowerCase();
                const userRole = card.getAttribute('data-role');

                const matchesSearch = userName.includes(searchInput);
                const matchesRole = selectedRole === "all" || selectedRole === userRole;

                if (matchesSearch && matchesRole) {
                    card.style.display = ''; 
                } else {
                    card.style.display = 'none'; 
                }
            });
        }

        document.getElementById('roleFilter').addEventListener('change', filterUsers);

        // Infinite Scroll with Intersection Observer
        let page = 1;
        let loading = false;
        const userList = document.getElementById('userList');
        const loadingElement = document.getElementById('loading');

        const observer = new IntersectionObserver((entries) => {
            const firstEntry = entries[0];
            if (firstEntry.isIntersecting && !loading) {
                loadMoreUsers();
            }
        }, {
            threshold: 1.0,
            rootMargin: '100px'
        });

        observer.observe(loadingElement);

        function loadMoreUsers() {
            if (loading) return;
            
            loading = true;
            loadingElement.classList.remove('hidden');
            
            fetch(`/users?page=${++page}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newUsers = doc.getElementById('userList').innerHTML;
                    
                    if (newUsers.trim()) {
                        userList.innerHTML += newUsers;
                        filterUsers(); // Alkalmazzuk a jelenlegi szűrőket az új elemekre
                    } else {
                        observer.unobserve(loadingElement); // Ha nincs több betöltendő elem, leállítjuk a megfigyelést
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    loading = false;
                    loadingElement.classList.add('hidden');
                });
        }
    </script>
</x-app-layout>