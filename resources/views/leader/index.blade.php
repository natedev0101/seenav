<x-app-layout>
    <div class="p-2 w-full space-y-4" x-data="{ 
        activeTab: localStorage.getItem('leaderPanelTab') || 'home',
        setActiveTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('leaderPanelTab', tab);
        }
    }">
        <div class="bg-gray-800/50 shadow-md rounded-lg p-2 mb-4">
            <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 bg-gray-700/30 p-2 rounded-lg">
                <button @click="setActiveTab('home')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'home' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Főoldal</span>
                </button>
                <button @click="setActiveTab('requests')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'requests' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Igénylések</span>
                </button>
                <button @click="setActiveTab('stats')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'stats' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Adatbázis</span>
                </button>
                <button @click="setActiveTab('add')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'add' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tag hozzáadása</span>
                </button>
                <button @click="setActiveTab('blacklist')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'blacklist' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    <span>Kilépések és feketelista</span>
                </button>
                <button @click="setActiveTab('pending')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'pending' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>Megerősítésre váró elemek</span>
                </button>
                <button @click="setActiveTab('duty')" :class="{ 'from-blue-600/20 to-blue-700/20': activeTab === 'duty' }" class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-3 md:px-4 py-2.5 md:py-2 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Szolgálat</span>
                </button>
            </div>
        </div>

        <div x-show="activeTab === 'home'" x-transition>
            @include('leader.home')
        </div>

        <div x-show="activeTab === 'requests'" x-transition>
            @include('leader.requests')
        </div>

        <div x-show="activeTab === 'stats'" x-transition class="w-full px-2">
            @include('leader.stats')
        </div>

        <div x-show="activeTab === 'add'" x-transition>
            @include('leader.add')
        </div>

        <div x-show="activeTab === 'blacklist'" x-transition>
            @include('leader.blacklist')
        </div>

        <div x-show="activeTab === 'pending'" x-transition>
            @include('leader.pending')
        </div>

        <div x-show="activeTab === 'duty'" x-transition>
            @include('leader.duty')
        </div>
        
        <div class="h-px bg-gradient-to-r from-gray-700/50 via-gray-500/50 to-gray-700/50"></div>
    </div>
</x-app-layout>