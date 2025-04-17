<div x-data="stats" class="w-full !max-w-none -mx-6 lg:-mx-8">
    <!-- Értesítés komponens -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         :class="{
            'bg-green-500/10 text-green-400': notification.type === 'success',
            'bg-red-500/10 text-red-400': notification.type === 'error'
         }"
         class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50">
        <p x-text="notification.message"></p>
    </div>

    <div class="bg-gray-800/50 shadow-md rounded-lg p-2 w-full max-w-full">
        <!-- Statisztikai sáv -->
        <div class="bg-gray-700/50 rounded-lg mb-4 flex flex-col justify-center px-4 py-2 space-y-1">
            <div class="flex items-center space-x-2">
                <span class="text-gray-400 text-sm">Létszám:</span>
                <span class="text-orange-400 text-sm" x-text="users.length + ' fő'"></span>
                <span class="text-gray-400 text-sm">(</span>
                <span class="text-gray-400 text-sm" x-text="users.filter(u => u.role === 'Tag').length + ' tag'"></span>
                <span class="text-gray-400 text-sm">,</span>
                <span class="text-green-400 text-sm" x-text="users.filter(u => u.role === 'Leader').length + ' leader'"></span>
                <span class="text-gray-400 text-sm">,</span>
                <span class="text-blue-400 text-sm" x-text="users.filter(u => u.role === 'Tiszt').length + ' tiszt'"></span>
                <span class="text-gray-400 text-sm">,</span>
                <span class="text-purple-400 text-sm" x-text="users.filter(u => u.role === 'Webmester').length + ' webmester'"></span>
                <span class="text-gray-400 text-sm">)</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-400 text-sm">Osztály:</span>
                <template x-if="subdivisions.length > 0">
                    <div class="flex items-center flex-wrap gap-2">
                        <template x-for="(subdivision, index) in subdivisions" :key="subdivision.id">
                            <div class="flex items-center">
                                <a :href="'/alosztalyok/' + subdivision.id + '/felhasznalok'" class="flex items-center group">
                                    <span class="text-white text-sm" x-text="subdivision.users_count + ' fő'"></span>
                                    <span class="text-sm ml-1" :style="{ color: subdivision.color }" x-text="subdivision.name"></span>
                                </a>
                                <template x-if="index < subdivisions.length - 1">
                                    <span class="text-gray-400 text-sm mx-1">+</span>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Betöltő animáció -->
        <div x-show="isLoading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-400"></div>
        </div>

        <!-- Hibalista -->
        <div x-show="!isLoading && users.length === 0" class="bg-red-500/10 text-red-400 p-3 rounded-lg text-sm mb-4">
            Nincsenek megjeleníthető tagok.
        </div>

        <!-- Mobil nézet -->
        <div class="block md:hidden space-y-4">
            <template x-for="user in users" :key="user.id">
                <div class="bg-gray-800/50 rounded-lg p-4 space-y-3"
                     :class="{
                        'border-2 border-red-400/30 rounded-xl': showHighlightedUsers && user.plus_points > 5,
                        'transition-colors': true
                     }">
                    <div class="flex justify-between items-center">
                        <a :href="'/felhasznalok/' + user.id" class="text-gray-300 hover:text-white transition-colors" x-text="user.charactername"></a>
                        <div class="flex space-x-1">
                            <button x-show="user.role !== 'Webmester'" 
                                    @click="promoteUser(user.id)" 
                                    :class="{
                                        'bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300': !user.is_max_rank,
                                        'bg-gray-500/10 text-gray-600 cursor-not-allowed': user.is_max_rank
                                    }"
                                    class="p-1 rounded-lg transition-colors"
                                    :disabled="user.is_max_rank"
                                    :title="user.is_max_rank ? 'Nem léptethető elő (legmagasabb rang)' : 'Előléptetés'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button x-show="user.role !== 'Webmester'" 
                                    @click="demoteUser(user.id)" 
                                    :class="{
                                        'bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300': !user.is_min_rank,
                                        'bg-gray-500/10 text-gray-600 cursor-not-allowed': user.is_min_rank
                                    }"
                                    class="p-1 rounded-lg transition-colors"
                                    :disabled="user.is_min_rank"
                                    :title="user.is_min_rank ? 'Nem fokozható le (legalacsonyabb rang)' : 'Lefokozás'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-400">Rendfokozat:</span>
                            <template x-if="user.rank !== 'NR'">
                                <a :href="'/rangok/' + user.rank_id + '/felhasznalok'" class="inline-block">
                                    <span :style="{ backgroundColor: user.rank_color + '1A', color: user.rank_color }" 
                                          class="block px-2 py-1 rounded-md hover:brightness-110 transition-all" 
                                          x-text="user.rank"></span>
                                </a>
                            </template>
                            <template x-if="user.rank === 'NR'">
                                <span class="bg-red-500/10 text-red-400 block px-2 py-1 rounded-md" x-text="user.rank"></span>
                            </template>
                        </div>
                        <div>
                            <span class="text-gray-400">Alosztály:</span>
                            <template x-if="user.role === 'Webmester'">
                                <span class="bg-red-500/10 text-red-400 block px-2 py-1 rounded-md">NR</span>
                            </template>
                            <template x-if="user.role !== 'Webmester'">
                                <div>
                                    <template x-if="user.subdivisions && user.subdivisions.length > 0">
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="subdivision in user.subdivisions" :key="subdivision.id">
                                                <a :href="'/alosztalyok/' + subdivision.id + '/felhasznalok'" class="inline-block">
                                                    <span :style="{ backgroundColor: subdivision.color + '1A', color: subdivision.color }" 
                                                          class="block px-2 py-1 rounded-md hover:brightness-110 transition-all" 
                                                          x-text="subdivision.name"></span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!user.subdivisions || user.subdivisions.length === 0">
                                        <span class="bg-gray-500/10 text-gray-400 block px-2 py-1 rounded-md">Nincs</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div>
                            <span class="text-gray-400">Pont:</span>
                            <div class="flex items-center space-x-2">
                                <template x-if="user.rank !== 'NR'">
                                    <div class="flex items-center space-x-2">
                                        <button @click="removePoint(user.id)" class="text-red-400 hover:text-red-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span class="block text-gray-300" 
                                              :class="{
                                                'text-purple-300': user.plus_points >= 1 && user.plus_points <= 4,
                                                'text-purple-500': user.plus_points >= 5 && user.plus_points <= 7,
                                                'text-red-500': user.plus_points >= 8 && user.plus_points <= 10,
                                                'text-yellow-400': user.plus_points > 10
                                              }"
                                              x-text="user.plus_points"></span>
                                        <button @click="addPoint(user.id)" class="text-green-400 hover:text-green-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <template x-if="user.rank === 'NR'">
                                    <div class="flex justify-center">
                                        <span class="text-gray-300" x-text="user.plus_points"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-400">Figyu:</span>
                            <span class="block text-gray-300" x-text="user.warnings"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Utolsó Rangup:</span>
                            <span class="block text-gray-300" x-text="user.last_rank_up"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Utolsó Szolgi:</span>
                            <span class="block text-gray-300" x-text="user.last_service"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Szolgi (óra):</span>
                            <span class="block text-gray-300" x-text="user.total_service_time"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Utolsó Duty:</span>
                            <span class="block text-gray-300" x-text="user.last_duty"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Rangup:</span>
                            <span class="block text-gray-300" x-text="user.rank_up"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Pontok:</span>
                            <span class="block text-gray-300" x-text="user.points"></span>
                        </div>
                        <div>
                            <span class="text-gray-400">Műveletek:</span>
                            <div class="flex space-x-1">
                                <button x-show="user.role !== 'Webmester'" @click="promoteUser(user.id)" class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                                <button x-show="user.role !== 'Webmester'" @click="demoteUser(user.id)" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Tablet/Desktop nézet -->
        <div x-show="!isLoading && users.length > 0" class="hidden md:block w-full">
            <div class="overflow-x-auto w-full">
                <table class="w-full table-auto">
                    <thead class="bg-gray-700/50">
                        <tr>
                            <th @click="sortBy('charactername')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center space-x-1">
                                    <span>Név</span>
                                    <template x-if="currentSort.column === 'charactername'">
                                        <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">
                                Rendfokozat
                            </th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">
                                Osztály
                            </th>
                            <th @click="sortBy('plus_points')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center space-x-2">
                                    <span>Pont</span>
                                    <button @click.stop="showHighlightedUsers = !showHighlightedUsers" 
                                            :class="{
                                                'bg-yellow-500/20 text-yellow-400': showHighlightedUsers,
                                                'bg-gray-500/10 text-gray-400': !showHighlightedUsers
                                            }"
                                            class="px-2 py-1 rounded-md text-xs lg:text-sm hover:brightness-110 transition-all">
                                        5+
                                    </button>
                                    <template x-if="currentSort.column === 'plus_points'">
                                        <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </th>
                            <th @click="sortBy('warnings')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center space-x-1">
                                    <span>Figyu</span>
                                    <template x-if="currentSort.column === 'warnings'">
                                        <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </th>
                            <th @click="sortBy('last_rank_up')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center space-x-1">
                                    <span>Utolsó RangUp</span>
                                    <template x-if="currentSort.column === 'last_rank_up'">
                                        <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </th>
                            <th @click="sortBy('days_in_rank')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center space-x-1">
                                    <span>Eltöltött napok a rendfokozaton</span>
                                    <template x-if="currentSort.column === 'days_in_rank'">
                                        <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Inak napok</span>
                                </div>
                            </th>
                            <th @click="sortBy('total_service_time')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center space-x-1">
                                    <span>Duty</span>
                                    <template x-if="currentSort.column === 'total_service_time'">
                                        <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </div>
                            </th>
                            <th @click="sortBy('next_rank_up')" class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-300 hover:bg-gray-600/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-1">
                                        <span>Következő RangUp</span>
                                        <template x-if="currentSort.column === 'next_rank_up'">
                                            <svg x-show="currentSort.direction === 'asc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                            <svg x-show="currentSort.direction === 'desc'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </template>
                                    </div>
                                    <button @click.stop="showPromotionDueUsers = !showPromotionDueUsers" 
                                            class="bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300 p-1.5 rounded-lg transition-colors"
                                            :class="{ 'bg-green-500/30': showPromotionDueUsers }">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </th>
                            <th class="px-2 py-2 text-left text-xs lg:text-sm font-medium text-gray-400 uppercase tracking-wider">
                                Rangup
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800/30">
                        <template x-for="user in users" :key="user.id">
                            <tr :class="{
                                    'border-2 border-red-400/30 rounded-xl': showHighlightedUsers && user.plus_points > 5,
                                    'border-2 border-green-400/30 rounded-xl': showPromotionDueUsers && (user.promotion_days <= 0 || user.custom_promotion_days <= 0),
                                    'border-2 border-yellow-400/30 rounded-xl': showPromotionDueUsers && (
                                        (user.promotion_days > 0 && user.promotion_days <= 3) || 
                                        (user.custom_promotion_days > 0 && user.custom_promotion_days <= 3)
                                    ),
                                    'hover:bg-gray-700/30': true,
                                    'transition-colors': true
                                }">
                                <td class="px-2 py-2">
                                    <a :href="'/felhasznalok/' + user.id" class="text-xs lg:text-sm text-blue-400 hover:text-blue-300 whitespace-nowrap transition-colors bg-blue-500/10 px-2 py-1 rounded-md" x-text="user.charactername"></a>
                                </td>
                                <td class="px-2 py-2">
                                    <template x-if="user.rank !== 'NR'">
                                        <a :href="'/rangok/' + user.rank_id + '/felhasznalok'" class="inline-block">
                                            <span :style="{ backgroundColor: user.rank_color + '1A', color: user.rank_color }" 
                                                  class="px-2 py-1 rounded-md text-xs lg:text-sm whitespace-nowrap hover:brightness-110 transition-all" 
                                                  x-text="user.rank"></span>
                                        </a>
                                    </template>
                                    <template x-if="user.rank === 'NR'">
                                        <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-md text-xs lg:text-sm whitespace-nowrap">NR</span>
                                    </template>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <template x-if="user.role === 'Webmester'">
                                        <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-md text-xs lg:text-sm whitespace-nowrap">NR</span>
                                    </template>
                                    <template x-if="user.role !== 'Webmester'">
                                        <div>
                                            <template x-if="user.subdivisions && user.subdivisions.length > 0">
                                                <div class="flex items-center gap-1" x-data="{ showAll: false }">
                                                    <!-- Első alosztály -->
                                                    <a :href="'/alosztalyok/' + user.subdivisions[0].id + '/felhasznalok'" class="inline-block">
                                                        <span :style="{ backgroundColor: user.subdivisions[0].color + '1A', color: user.subdivisions[0].color }" 
                                                              class="px-2 py-1 rounded-md text-xs lg:text-sm whitespace-nowrap hover:brightness-110 transition-all" 
                                                              x-text="user.subdivisions[0].name"></span>
                                                    </a>

                                                    <!-- További alosztályok gomb -->
                                                    <template x-if="user.subdivisions.length > 1">
                                                        <div class="relative">
                                                            <button @click="showAll = !showAll" 
                                                                    class="text-gray-400 hover:text-white transition-colors text-sm px-2 py-1 rounded-md hover:bg-gray-700/50">
                                                                ...
                                                            </button>

                                                            <!-- További alosztályok popup -->
                                                            <div x-show="showAll"
                                                                 @click.away="showAll = false"
                                                                 class="absolute left-0 mt-1 py-1 bg-gray-800 rounded-lg shadow-lg z-50">
                                                                <template x-for="subdivision in user.subdivisions.slice(1)" :key="subdivision.id">
                                                                    <a :href="'/alosztalyok/' + subdivision.id + '/felhasznalok'" 
                                                                       class="block px-3 py-1.5 hover:bg-gray-700/50 transition-colors whitespace-nowrap">
                                                                        <span :style="{ color: subdivision.color }" 
                                                                              class="text-xs lg:text-sm"
                                                                              x-text="subdivision.name"></span>
                                                                    </a>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="!user.subdivisions || user.subdivisions.length === 0">
                                                <span class="bg-gray-500/10 text-gray-400 px-2 py-1 rounded-md text-xs lg:text-sm whitespace-nowrap">Nincs</span>
                                            </template>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-2 py-2">
                                    <template x-if="user.rank !== 'NR'">
                                        <div class="flex items-center space-x-2">
                                            <button @click="removePoint(user.id)" class="text-red-400 hover:text-red-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="text-xs lg:text-sm text-gray-300 whitespace-nowrap"
                                                  :class="{
                                                    'text-purple-300': user.plus_points >= 1 && user.plus_points <= 4,
                                                    'text-purple-500': user.plus_points >= 5 && user.plus_points <= 7,
                                                    'text-red-500': user.plus_points >= 8 && user.plus_points <= 10,
                                                    'text-yellow-400': user.plus_points > 10
                                                  }"
                                                  x-text="user.plus_points"></span>
                                            <button @click="addPoint(user.id)" class="text-green-400 hover:text-green-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="user.rank === 'NR'">
                                        <div class="flex justify-center">
                                            <span class="text-xs lg:text-sm text-gray-300 whitespace-nowrap" x-text="user.plus_points"></span>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300 whitespace-nowrap" x-text="user.warnings"></td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300 whitespace-nowrap" x-text="user.last_rank_up"></td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300">
                                    <template x-if="user.role !== 'Webmester'">
                                        <span x-text="user.days_in_rank + ' nap'"></span>
                                    </template>
                                    <template x-if="user.role === 'Webmester'">
                                        <span>NR</span>
                                    </template>
                                </td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300 whitespace-nowrap">0</td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300 whitespace-nowrap" x-text="user.total_service_time + ' perc'"></td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300">
                                    <span x-show="user.role !== 'Webmester'" class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-md" x-text="user.promotion_days + ' nap'"></span>
                                    <span x-show="user.role === 'Webmester'" class="bg-gray-500/10 text-gray-400 px-3 py-1 rounded-md">-</span>
                                </td>
                                <td class="px-2 py-2 text-xs lg:text-sm text-gray-300">
                                    <div class="flex space-x-1">
                                        <button x-show="user.role !== 'Webmester'" 
                                                @click="promoteUser(user.id)" 
                                                :class="{
                                                    'bg-green-500/10 hover:bg-green-500/20 text-green-400 hover:text-green-300': !user.is_max_rank,
                                                    'bg-gray-500/10 text-gray-600 cursor-not-allowed': user.is_max_rank
                                                }"
                                                class="p-1 rounded-lg transition-colors"
                                                :disabled="user.is_max_rank"
                                                :title="user.is_max_rank ? 'Nem léptethető elő (legmagasabb rang)' : 'Előléptetés'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                        <button x-show="user.role !== 'Webmester'" 
                                                @click="demoteUser(user.id)" 
                                                :class="{
                                                    'bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300': !user.is_min_rank,
                                                    'bg-gray-500/10 text-gray-600 cursor-not-allowed': user.is_min_rank
                                                }"
                                                class="p-1 rounded-lg transition-colors"
                                                :disabled="user.is_min_rank"
                                                :title="user.is_min_rank ? 'Nem fokozható le (legalacsonyabb rang)' : 'Lefokozás'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stats', () => ({
            users: [],
            subdivisions: [],
            showHighlightedUsers: false,
            showPromotionDueUsers: false,
            isLoading: true,
            currentSort: {
                column: 'charactername',
                direction: 'asc'
            },
            notification: {
                show: false,
                message: '',
                type: 'success'
            },
            async init() {
                await this.fetchData();
                await this.fetchSubdivisions();
            },
            showNotification(message, type = 'success') {
                this.notification.message = message;
                this.notification.type = type;
                this.notification.show = true;
                setTimeout(() => {
                    this.notification.show = false;
                }, 3000);
            },
            async fetchData() {
                try {
                    const response = await fetch(`/api/leader/stats?sort=${this.currentSort.column}&direction=${this.currentSort.direction}`);
                    const data = await response.json();
                    this.users = data.users.map(user => ({
                        ...user,
                        subdivisions: user.subdivisions || []
                    }));
                    this.currentSort = data.currentSort;
                } catch (error) {
                    console.error('Hiba történt:', error);
                    this.showNotification('Hiba történt!', 'error');
                } finally {
                    this.isLoading = false;
                }
            },
            async fetchSubdivisions() {
                try {
                    const response = await fetch('/api/leader/subdivisions');
                    const data = await response.json();
                    this.subdivisions = data.subdivisions;
                } catch (error) {
                    console.error('Hiba történt:', error);
                    this.showNotification('Hiba történt!', 'error');
                }
            },
            async addPoint(userId) {
                try {
                    const response = await fetch(`/api/leader/points/add/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification(data.message);
                        await this.fetchData();
                    } else {
                        this.showNotification(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Hiba történt:', error);
                    this.showNotification('Hiba történt!', 'error');
                }
            },
            async removePoint(userId) {
                try {
                    const response = await fetch(`/api/leader/points/remove/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.showNotification(data.message);
                        await this.fetchData();
                    } else {
                        this.showNotification(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Hiba történt:', error);
                    this.showNotification('Hiba történt!', 'error');
                }
            },
            async promoteUser(userId) {
                if (this.users.find(u => u.id === userId).is_max_rank) return;
                
                try {
                    const response = await fetch(`/api/leader/promote/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Hiba történt az előléptetés során');
                    }
                    
                    this.showNotification(data.message);
                    await this.fetchData();
                } catch (error) {
                    this.showNotification(error.message, 'error');
                    console.error('Hiba:', error);
                }
            },
            async demoteUser(userId) {
                if (this.users.find(u => u.id === userId).is_min_rank) return;
                
                try {
                    const response = await fetch(`/api/leader/demote/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Hiba történt a lefokozás során');
                    }
                    
                    this.showNotification(data.message);
                    await this.fetchData();
                } catch (error) {
                    this.showNotification(error.message, 'error');
                    console.error('Hiba:', error);
                }
            },
            sortBy(column) {
                // Ha ugyanarra az oszlopra kattintunk, megfordítjuk a rendezés irányát
                if (this.currentSort.column === column) {
                    this.currentSort.direction = this.currentSort.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    // Ha új oszlopra kattintunk, alapértelmezetten növekvő sorrendbe rendezünk
                    this.currentSort.direction = 'asc';
                    this.currentSort.column = column;
                }
                
                // Frissítjük a felhasználók listáját az új rendezéssel
                this.fetchData();
            },
        }));
    });
    </script>
    @endpush
</div>
