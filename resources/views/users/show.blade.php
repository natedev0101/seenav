<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $user->charactername }} profilja
        </h2>
    </x-slot>

    <x-notification />

    <div class="min-h-screen bg-gray-900 py-6" x-data="{ 
        activePanel: null,
        showConfirm: false,
        confirmType: null,
        confirmAction: null,
        confirmMessage: '',
        modals: {
            rank: false,
            subdivisions: false
        },
        showNameEdit: false,
        newName: '{{ $user->charactername }}',
        originalName: '{{ $user->charactername }}',
        isProcessing: false,
        async saveName() {
            if (this.isProcessing) return;
            this.isProcessing = true;
            
            try {
                const response = await fetch('/felhasznalok/{{ $user->id }}/nev-valtoztatas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        new_name: this.newName
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNameEdit = false;
                    this.originalName = this.newName;
                    window.location.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sikeres módosítás!',
                        text: result.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hiba!',
                        text: result.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hiba!',
                    text: 'Hiba történt a név módosítása során!'
                });
            } finally {
                this.isProcessing = false;
            }
        },
        toggleModal(modalName) {
            if (modalName === 'rank') {
                this.modals.rank = !this.modals.rank;
                document.body.style.overflow = this.modals.rank ? 'hidden' : '';
            } else {
                this.modals.subdivisions = !this.modals.subdivisions;
                document.body.style.overflow = this.modals.subdivisions ? 'hidden' : '';
            }
        },
        togglePermission(type, current) {
            this.confirmType = type;
            this.confirmAction = () => $wire.togglePermission(type);
            this.confirmMessage = `Biztosan ${current ? 'elveszed' : 'megadod'} a ${
                type === 'webmaster' ? 'webmester' : 
                type === 'leader' ? 'leader' : 
                'leader adási'
            } jogot?`;
            this.showConfirm = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Vissza gomb -->
            <div class="mb-4">
                <a href="{{ url()->previous() }}" 
                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-2 rounded-lg transition-colors inline-flex items-center space-x-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Vissza</span>
                </a>
            </div>

            <!-- Profil kártya -->
            <div class="bg-gray-800/50 shadow-md rounded-lg overflow-hidden">
                @if(Auth::user()->isAdmin || Auth::user()->is_superadmin)
                    <div class="border-b border-gray-700/50">
                        <div class="p-6">
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-4">Műveletek</h3>
                            <div class="space-y-3">
                                <!-- Felhasználói adatok panel -->
                                <div class="user-data-panel"
                                     :class="{ 'user-data-panel-active': activePanel === 'user' }">
                                    <button @click="activePanel = activePanel === 'user' ? null : 'user'" 
                                            class="user-data-header group">
                                        <div class="user-data-icon-wrapper user-data-icon-wrapper-blue">
                                            <svg class="user-data-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <span class="user-data-title">Felhasználói adatok</span>
                                        <svg class="user-data-arrow" :class="{'rotate-180': activePanel === 'user'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="activePanel === 'user'" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform translate-y-0"
                                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                                         class="user-data-content">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <button @click="toggleModal('rank')" 
                                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors inline-flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                <span>Rang</span>
                                            </button>
                                            <button @click="toggleModal('subdivisions')" 
                                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors inline-flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span>Alosztály</span>
                                            </button>
                                            @livewire('user-medal', ['user' => $user])
                                        </div>
                                    </div>
                                </div>

                                <!-- Jogosultságok panel -->
                                <div class="permission-panel"
                                     :class="{ 'permission-panel-active': activePanel === 'permissions' }">
                                    <button @click="activePanel = activePanel === 'permissions' ? null : 'permissions'" 
                                            class="permission-header">
                                        <div class="permission-icon-wrapper permission-icon-wrapper-yellow">
                                            <svg class="permission-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <span class="permission-title">Jogosultságok</span>
                                        <svg class="permission-arrow" :class="{'rotate-180': activePanel === 'permissions'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="activePanel === 'permissions'" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform translate-y-0"
                                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                                         class="permission-content">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                            @if(Auth::user()->is_superadmin || Auth::user()->isAdmin)
                                            <button @click="togglePermission('webmaster', {{ $user->is_superadmin ? 'true' : 'false' }})" 
                                                    class="permission-button"
                                                    :class="{{ $user->is_superadmin ? 
                                                        "'permission-button-yellow-active'" : 
                                                        "'permission-button-yellow'" }}">
                                                <svg class="permission-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                <span>Webmester</span>
                                                <span class="ml-auto">{{ $user->is_superadmin ? 'Igen' : 'Nem' }}</span>
                                            </button>
                                            @endif

                                            @if(Auth::user()->isAdmin)
                                            <button @click="togglePermission('leader', {{ $user->isAdmin ? 'true' : 'false' }})" 
                                                    class="permission-button"
                                                    :class="{{ $user->isAdmin ? 
                                                        "'permission-button-blue-active'" : 
                                                        "'permission-button-blue'" }}">
                                                <svg class="permission-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span>Leader</span>
                                                <span class="ml-auto">{{ $user->isAdmin ? 'Igen' : 'Nem' }}</span>
                                            </button>

                                            <button @click="togglePermission('canGiveAdmin', {{ $user->canGiveAdmin ? 'true' : 'false' }})" 
                                                    class="permission-button"
                                                    :class="{{ $user->canGiveAdmin ? 
                                                        "'permission-button-green-active'" : 
                                                        "'permission-button-green'" }}">
                                                <svg class="permission-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                                <span>Leader-t adhat</span>
                                                <span class="ml-auto">{{ $user->canGiveAdmin ? 'Igen' : 'Nem' }}</span>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profilkép és felhasználói adatok -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
                        <!-- Profilkép és online státusz -->
                        <div class="relative flex-shrink-0 mb-4 md:mb-0">
                            <div class="w-24 h-24 rounded-lg border-2 {{ $user->is_online ? 'border-green-500/50' : 'border-gray-500/50' }} overflow-hidden">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}" 
                                     alt="Profilkép"
                                     class="w-full h-full object-cover" />
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full {{ $user->is_online ? 'bg-green-500' : 'bg-gray-500' }} border-2 border-gray-800"></div>
                        </div>

                        <!-- Név és rang -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <h1 class="group">
                                    <p class="text-2xl font-bold truncate {{ $user->is_superadmin ? 'bg-gradient-to-r from-purple-400 via-blue-400 to-purple-400 text-transparent bg-clip-text' : 'text-white' }} group-hover:text-blue-400 transition-colors duration-200">
                                        {{ $user->charactername }}
                                    </p>
                                </h1>
                                @if(Auth::user()->isAdmin)
                                <button class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors" 
                                        @click="showNameEdit = true">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                @endif
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm {{ $user->is_online ? 'bg-green-500/10 text-green-400' : 'bg-gray-500/10 text-gray-400' }}">
                                    {{ $user->is_online ? 'Online' : 'Offline' }}
                                </span>
                            </div>

                            <!-- Név szerkesztés modal -->
                            <div x-show="showNameEdit" 
                                 class="fixed inset-0 bg-gray-900/75 z-50 flex items-center justify-center"
                                 @click.self="showNameEdit = false">
                                <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full mx-4" @click.stop>
                                    <h3 class="text-lg font-medium text-white mb-4">Név módosítása</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400 mb-1">Új név</label>
                                            <input type="text" 
                                                   x-model="newName"
                                                   class="w-full bg-gray-700 border-gray-600 rounded-lg text-white px-3 py-2">
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button @click="showNameEdit = false"
                                                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                                Mégse
                                            </button>
                                            <button @click="saveName()"
                                                    :disabled="isProcessing || newName === originalName || !newName.trim()"
                                                    :class="{'opacity-50 cursor-not-allowed': isProcessing || newName === originalName || !newName.trim()}"
                                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white rounded-lg transition-colors">
                                                <span x-show="!isProcessing">Mentés</span>
                                                <span x-show="isProcessing">Feldolgozás...</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rangok és címkék -->
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if ($user->is_superadmin)
                                    <span class="bg-purple-500/10 text-purple-400 px-2 py-1 rounded-lg text-sm">
                                        Webmester
                                    </span>
                                @endif
                                @if ($user->isAdmin)
                                    <span class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-lg text-sm">
                                        Leader
                                    </span>
                                @endif
                                @if($user->rank)
                                    <button @click="toggleModal('rank')" 
                                            style="background-color: {{ $user->rank->color }}33; color: {{ $user->rank->color }}"
                                            class="text-sm px-2 py-1 rounded-lg hover:bg-opacity-30 transition-colors">
                                        {{ $user->rank->name }}
                                    </button>
                                @else
                                    <button @click="toggleModal('rank')" 
                                            class="bg-gray-500/10 text-gray-400 text-sm px-2 py-1 rounded-lg hover:bg-gray-500/20 transition-colors">
                                        Nincs rang
                                    </button>
                                @endif

                                @if($user->subdivisions->count() > 0)
                                    @foreach($user->subdivisions as $subdivision)
                                        <button @click="toggleModal('subdivisions')"
                                                style="background-color: {{ $subdivision->color }}33; color: {{ $subdivision->color }}"
                                                class="text-sm px-2 py-1 rounded-lg hover:bg-opacity-30 transition-colors">
                                            {{ $subdivision->name }}
                                        </button>
                                    @endforeach
                                @else
                                    <button @click="toggleModal('subdivisions')" 
                                            class="bg-gray-500/10 text-gray-400 text-sm px-2 py-1 rounded-lg hover:bg-gray-500/20 transition-colors">
                                        Nincs alosztály
                                    </button>
                                @endif

                                @if ($user->medal !== 'Nincs medal')
                                    <span class="bg-{{ $user->medal === 'Arany' ? 'yellow' : ($user->medal === 'Ezüst' ? 'gray' : 'orange') }}-500/10 
                                                text-{{ $user->medal === 'Arany' ? 'yellow' : ($user->medal === 'Ezüst' ? 'gray' : 'orange') }}-400 
                                                px-2 py-1 rounded-lg text-sm">
                                        {{ $user->medal }} medál
                                    </span>
                                @else
                                    @if(auth()->user()->isAdmin || auth()->user()->is_superadmin)
                                        <button @click="$dispatch('open-modal')" 
                                                class="bg-gray-500/10 hover:bg-gray-500/20 text-gray-400 hover:text-gray-300 px-2 py-1 rounded-lg text-sm transition-colors">
                                            Nincs medál
                                        </button>
                                    @else
                                        <span class="bg-gray-500/10 text-gray-400 px-2 py-1 rounded-lg text-sm">
                                            Nincs medál
                                        </span>
                                    @endif
                                @endif
                                @livewire('user-medal', ['user' => $user])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Felhasználói adatok grid -->
            @if(Auth::user()->isAdmin || Auth::user()->is_superadmin)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Játékadatok kártya -->
                <div class="lg:col-span-8 bg-gray-800/50 shadow-md rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-700/50">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-200">Játékadatok</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto rounded-lg">
                            <table class="game-data-table">
                                <thead>
                                    <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        <th class="p-4 w-[180px] xl:w-[220px]">Adat</th>
                                        <th class="p-4">Érték</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700/30">
                                    <!-- UCP Profil -->
                                    <tr class="transition-colors hover:bg-gray-700/20">
                                        <td class="p-4 flex items-center text-gray-300 font-medium">
                                            <svg class="w-4 h-4 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            UCP Profil
                                        </td>
                                        <td class="p-4 text-gray-400">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ $user->character_id }}" 
                                                   class="text-blue-400 hover:text-blue-300 transition-colors">
                                                    UCP profil megtekintése
                                                </a>
                                            </div>
                                            <div x-data="{ 
                                                isEditing: false, 
                                                value: '{{ preg_replace('/.*?(\d+)$/', '$1', $user->character_id) }}',
                                                originalValue: '{{ preg_replace('/.*?(\d+)$/', '$1', $user->character_id) }}',
                                                error: '',
                                                async save() {
                                                    try {
                                                        const response = await fetch('/admin/users/{{ $user->id }}/game-data', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            },
                                                            body: JSON.stringify({
                                                                field: 'character_id',
                                                                value: 'https://ucp.see-game.com/v4/character/' + this.value
                                                            })
                                                        });
                                                        const data = await response.json();
                                                        if (data.success) {
                                                            this.error = '';
                                                            this.originalValue = this.value;
                                                            this.isEditing = false;
                                                            window.location.reload();
                                                        } else {
                                                            this.error = data.message;
                                                        }
                                                    } catch (error) {
                                                        this.error = 'Hiba történt a mentés során!';
                                                    }
                                                }
                                            }"
                                            class="relative mt-2">
                                                <div x-show="!isEditing" 
                                                    class="flex items-center gap-2">
                                                    <span class="bg-blue-500/10 px-3 py-1 rounded-md" x-text="originalValue"></span>
                                                    <button 
                                                        @click="isEditing = true; $nextTick(() => $refs.input.focus())"
                                                        class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div x-show="isEditing" 
                                                    class="flex items-center gap-2"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-90"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
                                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                                    <input
                                                        type="text"
                                                        x-ref="input"
                                                        x-model="value"
                                                        @keydown.enter="save()"
                                                        @keydown.escape="isEditing = false; value = originalValue"
                                                        @keypress="$event.key.match(/[0-9]/) === null ? $event.preventDefault() : null"
                                                        class="bg-gray-700/50 border border-gray-600 rounded px-3 py-2 focus:outline-none focus:border-blue-500 text-lg w-48 transition-all duration-300 transform scale-110"
                                                    >
                                                    <button 
                                                        @click="save()"
                                                        class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button 
                                                        @click="isEditing = false; value = originalValue"
                                                        class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div x-show="error" 
                                                    x-text="error"
                                                    class="text-red-500 text-sm mt-1">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Jelvényszám -->
                                    <tr class="transition-colors hover:bg-gray-700/20">
                                        <td class="p-4 flex items-center text-gray-300 font-medium">
                                            <svg class="w-4 h-4 text-yellow-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            Jelvényszám
                                        </td>
                                        <td class="p-4 text-gray-400" x-data="{ 
                                            isEditing: false, 
                                            value: '{{ $user->badge_number ?? 'Nincs elérhető információ' }}',
                                            originalValue: '{{ $user->badge_number ?? 'Nincs elérhető információ' }}',
                                            error: '',
                                            validate() {
                                                this.error = '';
                                                if (!/^\d+$/.test(this.value)) {
                                                    this.error = 'Csak számokat adhatsz meg!';
                                                    return false;
                                                }
                                                if (this.value.length > 20) {
                                                    this.error = 'Maximum 20 karakter lehet!';
                                                    return false;
                                                }
                                                return true;
                                            },
                                            async save() {
                                                if (!this.validate()) return;
                                                try {
                                                    const response = await fetch('/felhasznalok/{{ $user->id }}/jatekadatok', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                        },
                                                        body: JSON.stringify({
                                                            field: 'badge_number',
                                                            value: this.value
                                                        })
                                                    });
                                                    
                                                    const data = await response.json();
                                                    if (data.success) {
                                                        this.value = data.value;
                                                        this.originalValue = data.value;
                                                        this.isEditing = false;
                                                    } else {
                                                        alert(data.message || 'Hiba történt a mentés során!');
                                                        this.cancel();
                                                    }
                                                } catch (error) {
                                                    console.error('Hiba:', error);
                                                    alert('Hiba történt a mentés során!');
                                                    this.cancel();
                                                }
                                            },
                                            cancel() {
                                                this.value = this.originalValue;
                                                this.error = '';
                                                this.isEditing = false;
                                            }
                                        }">
                                            <div class="flex items-center gap-2">
                                                <template x-if="!isEditing">
                                                    <div @click="isEditing = true" class="cursor-pointer flex items-center gap-2">
                                                        <div class="edit-transition inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 hover:bg-blue-500/20">
                                                            <span x-text="originalValue"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="isEditing">
                                                    <div class="flex flex-col gap-1 zoom-in-enter">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" 
                                                                   x-model="value"
                                                                   @keydown.enter="save()"
                                                                   @keydown.escape="cancel()"
                                                                   @input="validate()"
                                                                   @keypress="$event.key.match(/[0-9]/) === null ? $event.preventDefault() : null"
                                                                   maxlength="20"
                                                                   pattern="[0-9]*"
                                                                   inputmode="numeric"
                                                                   class="bg-gray-800 text-gray-300 text-xs rounded-full px-3 py-2 focus:outline-none focus:border-blue-500 text-lg w-48 transition-all duration-300 transform scale-110"
                                                                   :class="{ 'input-error': error }"
                                                                   @click.away="cancel()">
                                                            
                                                            <div class="flex items-center gap-1">
                                                                <button @click="save()" 
                                                                        class="text-green-400 hover:text-green-300 transition-colors p-1 rounded-full hover:bg-green-400/10"
                                                                        :disabled="error"
                                                                        :class="{ 'opacity-50 cursor-not-allowed': error }">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="cancel()" class="text-red-400 hover:text-red-300 transition-colors p-1 rounded-full hover:bg-red-400/10">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div x-show="error" 
                                                             x-text="error"
                                                             class="text-xs text-red-400 px-2.5">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Játszott idő -->
                                    <tr class="transition-colors hover:bg-gray-700/20">
                                        <td class="p-4 flex items-center text-gray-300 font-medium">
                                            <svg class="w-4 h-4 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Játszott idő
                                        </td>
                                        <td class="p-4 text-gray-400" x-data="{ 
                                            isEditing: false, 
                                            value: '{{ $user->played_minutes ? number_format($user->played_minutes, 0, ',', ' ') . ' perc' : 'Nincs elérhető információ' }}',
                                            originalValue: '{{ $user->played_minutes ? number_format($user->played_minutes, 0, ',', ' ') . ' perc' : 'Nincs elérhető információ' }}',
                                            async save() {
                                                try {
                                                    const response = await fetch('/felhasznalok/{{ $user->id }}/jatekadatok', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                        },
                                                        body: JSON.stringify({
                                                            field: 'played_minutes',
                                                            value: this.value
                                                        })
                                                    });
                                                    
                                                    const data = await response.json();
                                                    if (data.success) {
                                                        this.originalValue = this.value;
                                                        this.isEditing = false;
                                                    } else {
                                                        alert('Hiba történt a mentés során!');
                                                        this.cancel();
                                                    }
                                                } catch (error) {
                                                    alert('Hiba történt a mentés során!');
                                                    this.cancel();
                                                }
                                            },
                                            cancel() {
                                                this.value = this.originalValue;
                                                this.isEditing = false;
                                            }
                                        }">
                                            <div class="flex items-center gap-2">
                                                <template x-if="!isEditing">
                                                    <div @click="isEditing = true" class="cursor-pointer flex items-center gap-2">
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 hover:bg-blue-500/20">
                                                            <span x-text="value"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="isEditing">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" 
                                                               x-model="value"
                                                               @keydown.enter="save()"
                                                               @keydown.escape="cancel()"
                                                               class="bg-gray-800 text-gray-300 text-xs rounded-full px-2.5 py-0.5 border border-blue-500/30 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                                               @click.away="cancel()">
                                                        
                                                        <div class="flex items-center gap-1">
                                                            <button @click="save()" class="text-green-400 hover:text-green-300 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </button>
                                                            <button @click="cancel()" class="text-red-400 hover:text-red-300 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Felvétel dátuma -->
                                    <tr class="transition-colors hover:bg-gray-700/20">
                                        <td class="p-4 flex items-center text-gray-300 font-medium">
                                            <svg class="w-4 h-4 text-purple-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 01-2 2V9a2 2 0 012-2v-6a2 2 0 012 2h3m-6 0a2 2 0 012 2v6a2 2 0 002 2v-6a2 2 0 012-2h3m-6 0a2 2 0 012 2v6a2 2 0 002 2v-6a2 2 0 012-2z"></path>
                                            </svg>
                                            Felvétel dátuma
                                        </td>
                                        <td class="p-4 text-gray-400" x-data="{ 
                                            isEditing: false, 
                                            value: '{{ $user->created_at ? $user->created_at->format('Y. m. j.') : 'Nincs elérhető információ' }}',
                                            originalValue: '{{ $user->created_at ? $user->created_at->format('Y. m. j.') : 'Nincs elérhető információ' }}',
                                            async save() {
                                                try {
                                                    const response = await fetch('/felhasznalok/{{ $user->id }}/jatekadatok', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                        },
                                                        body: JSON.stringify({
                                                            field: 'created_at',
                                                            value: this.value
                                                        })
                                                    });
                                                    
                                                    const data = await response.json();
                                                    if (data.success) {
                                                        this.originalValue = this.value;
                                                        this.isEditing = false;
                                                    } else {
                                                        alert('Hiba történt a mentés során!');
                                                        this.cancel();
                                                    }
                                                } catch (error) {
                                                    alert('Hiba történt a mentés során!');
                                                    this.cancel();
                                                }
                                            },
                                            cancel() {
                                                this.value = this.originalValue;
                                                this.isEditing = false;
                                            }
                                        }">
                                            <div class="flex items-center gap-2">
                                                <template x-if="!isEditing">
                                                    <div @click="isEditing = true" class="cursor-pointer flex items-center gap-2">
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 hover:bg-blue-500/20">
                                                            <span x-text="value"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="isEditing">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" 
                                                               x-model="value"
                                                               @keydown.enter="save()"
                                                               @keydown.escape="cancel()"
                                                               class="bg-gray-800 text-gray-300 text-xs rounded-full px-2.5 py-0.5 border border-blue-500/30 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                                               @click.away="cancel()">
                                                        
                                                        <div class="flex items-center gap-1">
                                                            <button @click="save()" class="text-green-400 hover:text-green-300 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </button>
                                                            <button @click="cancel()" class="text-red-400 hover:text-red-300 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Beajánló -->
                                    <tr class="transition-colors hover:bg-gray-700/20">
                                        <td class="p-4 flex items-center text-gray-300 font-medium">
                                            <svg class="w-4 h-4 text-pink-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Beajánló
                                        </td>
                                        <td class="p-4 text-gray-400" x-data="{ 
                                            isEditing: false, 
                                            value: '{{ $user->recommended_by ?? 'Nincs elérhető információ' }}',
                                            originalValue: '{{ $user->recommended_by ?? 'Nincs elérhető információ' }}',
                                            async save() {
                                                try {
                                                    const response = await fetch('/felhasznalok/{{ $user->id }}/jatekadatok', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                        },
                                                        body: JSON.stringify({
                                                            field: 'recommended_by',
                                                            value: this.value
                                                        })
                                                    });
                                                    
                                                    const data = await response.json();
                                                    if (data.success) {
                                                        this.originalValue = this.value;
                                                        this.isEditing = false;
                                                    } else {
                                                        alert('Hiba történt a mentés során!');
                                                        this.cancel();
                                                    }
                                                } catch (error) {
                                                    alert('Hiba történt a mentés során!');
                                                    this.cancel();
                                                }
                                            },
                                            cancel() {
                                                this.value = this.originalValue;
                                                this.isEditing = false;
                                            }
                                        }">
                                            <div class="flex items-center gap-2">
                                                <template x-if="!isEditing">
                                                    <div @click="isEditing = true" class="cursor-pointer flex items-center gap-2">
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 hover:bg-blue-500/20">
                                                            <span x-text="value"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="isEditing">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" 
                                                               x-model="value"
                                                               @keydown.enter="save()"
                                                               @keydown.escape="cancel()"
                                                               class="bg-gray-800 text-gray-300 text-xs rounded-full px-2.5 py-0.5 border border-blue-500/30 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                                               @click.away="cancel()">
                                                        
                                                        <div class="flex items-center gap-1">
                                                            <button @click="save()" class="text-green-400 hover:text-green-300 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </button>
                                                            <button @click="cancel()" class="text-red-400 hover:text-red-300 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Telefonszám -->
                                    <tr class="transition-colors hover:bg-gray-700/20">
                                        <td class="p-4 flex items-center text-gray-300 font-medium">
                                            <svg class="w-4 h-4 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Telefonszám
                                        </td>
                                        <td class="p-4 text-gray-400" x-data="{ 
                                            isEditing: false, 
                                            value: '{{ $user->phone_number ?? 'Nincs elérhető információ' }}',
                                            originalValue: '{{ $user->phone_number ?? 'Nincs elérhető információ' }}',
                                            error: '',
                                            validate() {
                                                this.error = '';
                                                if (!/^\d+$/.test(this.value)) {
                                                    this.error = 'Csak számokat adhatsz meg!';
                                                    return false;
                                                }
                                                if (this.value.length > 30) {
                                                    this.error = 'Maximum 30 karakter lehet!';
                                                    return false;
                                                }
                                                return true;
                                            },
                                            async save() {
                                                if (!this.validate()) return;
                                                try {
                                                    const response = await fetch('/felhasznalok/{{ $user->id }}/jatekadatok', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                        },
                                                        body: JSON.stringify({
                                                            field: 'phone_number',
                                                            value: this.value
                                                        })
                                                    });
                                                    
                                                    const data = await response.json();
                                                    if (data.success) {
                                                        this.value = data.value;
                                                        this.originalValue = data.value;
                                                        this.isEditing = false;
                                                    } else {
                                                        alert(data.message || 'Hiba történt a mentés során!');
                                                        this.cancel();
                                                    }
                                                } catch (error) {
                                                    console.error('Hiba:', error);
                                                    alert('Hiba történt a mentés során!');
                                                    this.cancel();
                                                }
                                            },
                                            cancel() {
                                                this.value = this.originalValue;
                                                this.error = '';
                                                this.isEditing = false;
                                            }
                                        }">
                                            <div class="flex items-center gap-2">
                                                <template x-if="!isEditing">
                                                    <div @click="isEditing = true" class="cursor-pointer flex items-center gap-2">
                                                        <div class="edit-transition inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 hover:bg-blue-500/20">
                                                            <span x-text="originalValue"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="isEditing">
                                                    <div class="flex flex-col gap-1 zoom-in-enter">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" 
                                                                   x-model="value"
                                                                   @keydown.enter="save()"
                                                                   @keydown.escape="cancel()"
                                                                   @input="validate()"
                                                                   @keypress="$event.key.match(/[0-9]/) === null ? $event.preventDefault() : null"
                                                                   maxlength="30"
                                                                   pattern="[0-9]*"
                                                                   inputmode="numeric"
                                                                   class="bg-gray-800 text-gray-300 text-xs rounded-full px-3 py-2 focus:outline-none focus:border-blue-500 text-lg w-48 transition-all duration-300 transform scale-110"
                                                                   :class="{ 'input-error': error }"
                                                                   @click.away="cancel()">
                                                            
                                                            <div class="flex items-center gap-1">
                                                                <button @click="save()" 
                                                                        class="text-green-400 hover:text-green-300 transition-colors p-1 rounded-full hover:bg-green-400/10"
                                                                        :disabled="error"
                                                                        :class="{ 'opacity-50 cursor-not-allowed': error }">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </button>
                                                                <button @click="cancel()" class="text-red-400 hover:text-red-300 transition-colors p-1 rounded-full hover:bg-red-400/10">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div x-show="error" 
                                                             x-text="error"
                                                             class="text-xs text-red-400 px-2.5">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
                <!-- Felhasználói statisztikák -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Figyelmeztetések -->
                    <div class="bg-gray-800/50 shadow-md rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13a2 2 0 01-3.844 2.838L6.646 21H2a2 2 0 01-2-2V9a2 2 0 012-2h9a2 2 0 012 2m6-1h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Figyelmeztetések</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($user->warnings->count() > 0)
                                <div class="space-y-4">
                                    @foreach($user->warnings as $warning)
                                        <div class="bg-gray-700/30 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-gray-300 font-medium">{{ $warning->reason }}</span>
                                                <span class="text-sm text-gray-400">{{ $warning->created_at->format('Y.m.d H:i') }}</span>
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                Kiadta: {{ $warning->issuer->charactername }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-gray-400">
                                    Nincsenek figyelmeztetések
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Rang és alosztály kártya -->
                    <div class="bg-gray-800/50 shadow-md rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-200">Rang és alosztály</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Rang -->
                            <div x-data="{ 
                                open: false,
                                isAdmin: {{ Auth::user()->isAdmin() ? 'true' : 'false' }},
                                isMaxRank: {{ $user->rank && $user->rank->id >= App\Models\Rank::getMaxId() ? 'true' : 'false' }},
                                isMinRank: {{ $user->rank && $user->rank->id <= 1 ? 'true' : 'false' }},
                                updateRank(userId, rankId) {
                                    fetch(`/api/users/${userId}/rang`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        },
                                        body: JSON.stringify({
                                            rank_id: rankId
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        window.location.reload();
                                    });
                                },
                                promoteUser(userId) {
                                    if (this.isMaxRank) return;
                                    
                                    fetch(`/api/users/${userId}/promote`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        window.location.reload();
                                    });
                                },
                                demoteUser(userId) {
                                    if (this.isMinRank) return;
                                    
                                    fetch(`/api/users/${userId}/demote`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        window.location.reload();
                                    });
                                }
                            }">
                                <div class="text-sm text-gray-400 mb-2">Rang</div>
                                <div class="flex items-center justify-between gap-2">
                                    <button @click="isAdmin && (open = !open)" 
                                            type="button"
                                            class="inline-flex items-center gap-1">
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                             style="background-color: {{ $user->rank?->color ?? '#374151' }}40; color: {{ $user->rank?->color ?? '#9CA3AF' }}">
                                            {{ $user->rank?->name ?? 'Nincs beállítva' }}
                                        </div>
                                        @if(Auth::user()->isAdmin())
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </button>
                                </div>

                                <!-- Modális ablak a rangok kiválasztásához -->
                                <div x-show="open" 
                                     class="fixed inset-0 z-[9999] overflow-hidden"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">
                                    <!-- Háttér overlay -->
                                    <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

                                    <!-- Modális tartalom -->
                                    <div class="relative min-h-screen flex items-center justify-center p-4">
                                        <div class="relative bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4" @click.stop>
                                            <h3 class="text-lg font-medium text-white mb-4">Rang módosítása</h3>
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-2 gap-2">
                                                    @foreach($ranks as $rank)
                                                        <button @click="updateRank({{ $user->id }}, {{ $rank->id }}); open = false"
                                                                class="w-full text-left p-3 rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-3"
                                                                :class="{ 'bg-gray-700': {{ $user->rank_id ?? 'null' }} === {{ $rank->id }} }">
                                                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $rank->color }}"></div>
                                                            <span class="font-medium" style="color: {{ $rank->color }}">
                                                                {{ $rank->name }}
                                                            </span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Alosztály -->
                            <div x-data="{ 
                                open: false,
                                isAdmin: {{ Auth::user()->isAdmin() ? 'true' : 'false' }},
                                updateSubdivisions(userId, subdivisionIds) {
                                    fetch(`/api/users/${userId}/subdivisions`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        },
                                        body: JSON.stringify({ subdivision_ids: subdivisionIds })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        window.location.reload();
                                    });
                                }
                            }">
                                <div class="text-sm text-gray-400 mb-2">Alosztály</div>
                                <div class="flex items-center justify-between gap-2">
                                    <button @click="isAdmin && (open = !open)" 
                                            type="button"
                                            class="inline-flex items-center gap-1">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($user->subdivisions as $subdivision)
                                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                     style="background-color: {{ $subdivision->color }}40; color: {{ $subdivision->color }}">
                                                    {{ $subdivision->name }}
                                                </div>
                                            @empty
                                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700/50 text-gray-400">
                                                    Nincs beállítva
                                                </div>
                                            @endforelse
                                        </div>
                                        @if(Auth::user()->isAdmin())
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </button>
                                </div>

                                <!-- Modal -->
                                <div x-show="open"
                                     class="fixed inset-0 z-50 overflow-hidden"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">
                                    <div class="flex items-center justify-center min-h-screen p-4">
                                        <!-- Háttér -->
                                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                                        </div>

                                        <!-- Modál kártya -->
                                        <div class="relative bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                            
                                            <!-- Fejléc -->
                                            <div class="border-t border-gray-700 px-4 py-3 flex justify-between items-center">
                                                <h3 class="text-lg font-medium text-white">Alosztályok módosítása</h3>
                                                <button @click="open = false" class="text-gray-400 hover:text-gray-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="px-4 py-4">
                                                <!-- Jelenlegi alosztályok -->
                                                <div class="mb-4">
                                                    <span class="text-sm text-gray-400">Jelenlegi alosztályok:</span>
                                                    <div class="mt-1 flex flex-wrap gap-1">
                                                        @forelse($user->subdivisions as $subdivision)
                                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                                 style="background-color: {{ $subdivision->color }}40; color: {{ $subdivision->color }}">
                                                                {{ $subdivision->name }}
                                                            </div>
                                                        @empty
                                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700/50 text-gray-400">
                                                                Nincs beállítva
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>

                                                <!-- Alosztályok listája -->
                                                <div class="grid grid-cols-2 gap-2">
                                                    <!-- Nincs alosztály opció -->
                                                    <div class="col-span-2 flex items-center p-2 rounded-lg hover:bg-gray-700/50 transition-colors">
                                                        <input type="checkbox" 
                                                               id="no-subdivision"
                                                               x-model="noSubdivision"
                                                               @change="if(noSubdivision) { document.querySelectorAll('input[type=checkbox]').forEach(cb => { if(cb.id !== 'no-subdivision') cb.checked = false; }); }"
                                                               class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-600 focus:ring-2">
                                                        <label for="no-subdivision" 
                                                               class="ml-2 w-full flex items-center justify-between cursor-pointer">
                                                            <span class="text-sm font-medium text-gray-300">Nincs alosztály</span>
                                                        </label>
                                                    </div>

                                                    @foreach(\App\Models\Subdivision::all() as $subdivision)
                                                        <div class="flex items-center p-2 rounded-lg hover:bg-gray-700/50 transition-colors">
                                                            <input type="checkbox" 
                                                                   id="subdivision-{{ $subdivision->id }}"
                                                                   name="subdivisions[]"
                                                                   value="{{ $subdivision->id }}"
                                                                   @checked($user->subdivisions->contains($subdivision))
                                                                   @change="if(this.checked) { document.getElementById('no-subdivision').checked = false; }"
                                                                   class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-600 focus:ring-2">
                                                            <label for="subdivision-{{ $subdivision->id }}" 
                                                                   class="ml-2 w-full flex items-center justify-between cursor-pointer">
                                                                <span class="text-sm font-medium text-gray-300">{{ $subdivision->name }}</span>
                                                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $subdivision->color }}"></div>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Gombok -->
                                            <div class="border-t border-gray-700 px-4 py-3 flex justify-end space-x-3">
                                                <button type="button"
                                                        @click="open = false"
                                                        class="inline-flex justify-center rounded-md border border-gray-500 px-4 py-2 bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    Mégse
                                                </button>
                                                <button type="button"
                                                        @click="updateSubdivisions({{ $user->id }}, document.getElementById('no-subdivision').checked ? [] : Array.from(document.querySelectorAll('input[name=\'subdivisions[]\']:checked')).map(cb => cb.value))"
                                                        class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Mentés
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jelszó reset gomb -->
                    @if(Auth::user()->isAdmin || Auth::user()->is_superadmin)
                        <div x-data="{ showLoading: false, showConfirm: false, newPassword: '' }" class="bg-gray-800/50 shadow-md rounded-lg overflow-hidden">
                            <div class="p-6">
                                @if($user->is_superadmin)
                                    <form action="{{ route('users.resetPassword', $user->id) }}" method="POST" 
                                        @submit.prevent="showLoading = true; 
                                        $dispatch('notice', {
                                            type: 'warning',
                                            text: 'Most lebuktál! Email küldése folyamatban...',
                                            timeout: 3000
                                        });
                                        setTimeout(() => { $el.submit(); }, 1000)">
                                        @csrf
                                        <button type="submit" 
                                            class="w-full bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 hover:text-yellow-300 p-3 rounded-lg transition-colors flex items-center justify-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                            <span>Jelszó visszaállítása</span>
                                        </button>
                                    </form>

                                    <!-- Loading Modal -->
                                    <div x-show="showLoading" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 z-50 overflow-y-auto" 
                                        aria-labelledby="modal-title" 
                                        role="dialog" 
                                        aria-modal="true">
                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div class="relative inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" @click.stop>
                                                <div class="p-6">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-yellow-400"></div>
                                                        <h3 class="mt-4 text-xl font-medium text-yellow-400">Most lebuktál!</h3>
                                                        <p class="mt-2 text-sm text-gray-400">Email küldése folyamatban...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <button type="button" 
                                        @click="showConfirm = true"
                                        class="w-full bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-3 rounded-lg transition-colors flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        <span>Jelszó visszaállítása</span>
                                    </button>

                                    <!-- Megerősítő Modal -->
                                    <div x-show="showConfirm" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 z-50 overflow-y-auto" 
                                        aria-labelledby="modal-title" 
                                        role="dialog" 
                                        aria-modal="true">
                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div class="relative inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="p-6">
                                                    <div class="flex flex-col">
                                                        <h3 class="text-xl font-medium text-white mb-4">Jelszó visszaállítása</h3>
                                                        <p class="text-gray-300 mb-4">Biztosan vissza szeretnéd állítani <strong>{{ $user->username }}</strong> jelszavát?</p>
                                                        <p class="text-gray-300 mb-4">A felhasználó új jelszava generálásra kerül, és a következő bejelentkezéskor kötelező lesz megváltoztatnia.</p>
                                                        
                                                        <div class="flex justify-end space-x-3 mt-4">
                                                            <button type="button" 
                                                                @click="showConfirm = false"
                                                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                                                                Mégsem
                                                            </button>
                                                            <form action="{{ route('users.resetPassword', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                                                                    Visszaállítás
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="mt-4 p-4 bg-red-500/10 rounded-lg border border-red-500/20">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-red-400">{{ session('error') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if (session()->has('success'))
                                    <div class="mt-4 p-4 bg-green-500/10 rounded-lg border border-green-500/20">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-sm text-green-400">{{ session('success') }}</span>
                                        </div>
                                        @if (session()->has('password'))
                                            <div class="mt-2 p-3 bg-gray-900/50 rounded-lg">
                                                <p class="text-sm text-gray-300">Az új jelszó: <code class="text-yellow-400">{{ session('password') }}</code></p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kapcsolat kártya --}}
                {{-- @if($user->is_superadmin)
                <div class="bg-gray-800/50 shadow-md rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-700/50">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002 2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Kapcsolat</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm text-gray-400 mb-2">Email cím</div>
                                <div class="bg-blue-500/10 px-3 py-1 rounded-md text-blue-400">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400 mb-2">Telefonszám</div>
                                <div class="bg-blue-500/10 px-3 py-1 rounded-md text-blue-400">
                                    {{ $user->phone }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif --}}
            </div>

            <style>
                .edit-transition {
                    transition: all 0.3s ease-in-out;
                }
                .edit-transition:hover {
                    transform: scale(1.02);
                }
                .zoom-in-enter {
                    animation: zoomIn 0.2s ease-out;
                }
                @keyframes zoomIn {
                    from {
                        opacity: 0;
                        transform: scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }
                .input-error {
                    @apply ring-2 ring-red-500/50 !important;
                }
            </style>

            @push('scripts')
            <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('userRank', () => ({
                    isMaxRank: false,
                    isMinRank: false,
                    isAdmin: {{ auth()->user()->isAdmin() ? 'true' : 'false' }},
                    notification: {
                        show: false,
                        message: '',
                        type: 'success'
                    },

                    init() {
                        this.checkRankLimits();
                    },

                    showNotification(message, type = 'success') {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: type,
                                message: message
                            }
                        }));
                    },

                    async checkRankLimits() {
                        try {
                            const response = await fetch(`/api/users/{{ $user->id }}/rank-limits`);
                            const data = await response.json();
                            this.isMaxRank = data.is_max_rank;
                            this.isMinRank = data.is_min_rank;
                        } catch (error) {
                            console.error('Hiba történt a rang limitek ellenőrzésekor:', error);
                            this.showNotification('Hiba történt!', 'error');
                        }
                    },

                    async updateRank(userId, rankId) {
                        try {
                            const response = await fetch(`/api/users/${userId}/rang`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    rank_id: rankId
                                })
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.showNotification('Rang sikeresen frissítve!');
                                window.location.reload();
                            } else {
                                throw new Error(data.message || 'Hiba történt a rang frissítése során');
                            }
                        } catch (error) {
                            this.showNotification(error.message, 'error');
                            console.error('Hiba:', error);
                        }
                    },

                    async promoteUser(userId) {
                        if (this.isMaxRank) return;
                        
                        try {
                            const response = await fetch(`/api/users/${userId}/promote`, {
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
                            window.location.reload();
                        } catch (error) {
                            this.showNotification(error.message, 'error');
                            console.error('Hiba:', error);
                        }
                    },

                    async demoteUser(userId) {
                        if (this.isMinRank) return;
                        
                        try {
                            const response = await fetch(`/api/users/${userId}/demote`, {
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
                            window.location.reload();
                        } catch (error) {
                            this.showNotification(error.message, 'error');
                            console.error('Hiba:', error);
                        }
                    }
                }));
            });
            </script>
            @endpush
        </x-app-layout>