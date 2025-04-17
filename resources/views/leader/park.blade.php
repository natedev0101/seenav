<x-app-layout>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('parkingMap', () => ({
                selectedSpot: null,
                spots: @json($spots),
                owned: @json($owned),
                occupancyRate: 0,

                init() {
                    this.calculateOccupancy();
                },

                calculateOccupancy() {
                    const totalSpots = this.spots.length;
                    const occupiedSpots = this.spots.filter(spot => spot.is_occupied).length;
                    this.occupancyRate = totalSpots > 0 ? (occupiedSpots / totalSpots) : 0;
                },

                async requestParkingSpot(spotId) {
                    try {
                        const response = await fetch('/api/parkings/request', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ spot_id: spotId })
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.successMessage = 'Parkolóhely sikeresen lefoglalva!';
                            this.errorMessage = '';
                            // Frissítjük a foglaltsági adatokat
                            this.calculateOccupancy();
                            // 2 másodperc múlva frissítjük az oldalt
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            this.errorMessage = data.error || 'Hiba történt a foglalás során';
                            this.successMessage = '';
                        }
                    } catch (error) {
                        this.errorMessage = 'Hiba történt a szerverrel való kommunikáció során';
                        this.successMessage = '';
                    }
                }
            }))
        })
    </script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Parkolóhely igénylés') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-blue-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
        </svg>
    </x-slot>

    <style>
        .park-slot {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .park-slot:hover:not(.reserved) {
            filter: brightness(1.2);
            box-shadow: inset 0 0 15px rgba(255, 255, 255, 0.2);
        }

        .park-slot:hover:not(.reserved)::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            animation: shine 1.5s infinite;
        }

        @keyframes shine {
            100% {
                left: 100%;
            }
        }

        .reserved {
            background-color: rgba(239, 68, 68, 0.2) !important;
            border-color: rgb(239, 68, 68) !important;
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
                <!-- Foglaltsági statisztika -->
                <div class="col-span-full">
                </div>
            </div>
            <div class="p-2 bg-gray-700/30 rounded-lg relative overflow-hidden">
                <!-- Foglaltsági statisztika -->
                <div class="absolute top-4 left-4 right-4 z-10">
                    <div class="bg-gray-800/90 backdrop-blur rounded-lg p-4 shadow-lg">
                        <div class="relative h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div 
                                class="absolute inset-y-0 left-0 bg-gradient-to-r from-green-500 to-red-500 transition-all duration-500"
                                :style="'width: ' + (occupancyRate * 100) + '%'"
                            ></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm">
                            <span class="text-green-400">Szabad</span>
                            <span class="text-gray-400" x-text="Math.round(occupancyRate * 100) + '%'"></span>
                            <span class="text-red-400">Foglalt</span>
                        </div>
                    </div>
                </div>

                <style>
                    .border-glow {
                        position: relative;
                    }
                    .border-glow::before {
                        content: '';
                        position: absolute;
                        inset: -2px;
                        background: linear-gradient(90deg, rgb(34 197 94) 0%, rgb(239 68 68) 100%);
                        z-index: -2;
                        opacity: var(--occupancy-opacity);
                        transition: opacity 0.5s ease;
                    }
                    .border-glow::after {
                        content: '';
                        position: absolute;
                        inset: 0;
                        background: rgb(17 24 39);
                        border-radius: 0.5rem;
                        z-index: -1;
                    }
                </style>

                <div 
                    class="border-glow rounded-lg"
                    x-init="$el.style.setProperty('--occupancy-opacity', occupancyRate)"
                    x-effect="$el.style.setProperty('--occupancy-opacity', occupancyRate)"
                >
                    <div class="relative z-10">
                        <div x-data="{ 
                            showDetails: false, 
                            selectedSpot: null,
                            errorMessage: '',
                            successMessage: '',
                            async requestSpot(spotId) {
                                try {
                                    const response = await fetch('/api/parkings/request', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        },
                                        body: JSON.stringify({ spot_id: spotId })
                                    });
                                    
                                    const data = await response.json();
                                    
                                    if (response.ok) {
                                        this.successMessage = 'Parkolóhely sikeresen lefoglalva!';
                                        this.errorMessage = '';
                                        // Frissítjük a foglaltsági adatokat
                                        await $parent.updateOccupancy();
                                        // 2 másodperc múlva frissítjük az oldalt
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 2000);
                                    } else {
                                        this.errorMessage = data.error || 'Hiba történt a foglalás során';
                                        this.successMessage = '';
                                    }
                                } catch (error) {
                                    this.errorMessage = 'Hiba történt a szerverrel való kommunikáció során';
                                    this.successMessage = '';
                                }
                            }
                        }" @keydown.escape.window="showDetails = false">
                            <!-- Térkép -->
                            <svg id="park-map" viewBox="0 0 1920 1080" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                                <!-- Háttérkép -->
                                <image href="{{ asset('images/park.png') }}" x="0" y="0" width="1920" height="1080"/>

                                <!-- Parkolóhelyek -->
                                @foreach($spots as $spot)
                                    @php
                                        // Koordináták átszámítása az 1920x1080-as viewportra
                                        $x = ($spot['position']['x'] / 1200) * 1920;
                                        $y = ($spot['position']['y'] / 500) * 1080;
                                        $width = (70 / 1200) * 1920;
                                        $height = (20 / 500) * 1080;
                                        $rotation = $spot['rotation'] ?? 0;
                                        if ($rotation == 90) {
                                            $temp = $width;
                                            $width = $height;
                                            $height = $temp;
                                        }

                                        // Spot adatok JSON formátumba alakítása
                                        $spotData = [
                                            'id' => $spot['id'],
                                            'number' => $spot['number'],
                                            'owner' => $spot['owner'] ?? null,
                                            'handled_by' => $spot['handled_by'] ?? null,
                                            'request_date' => $spot['request_date'] ?? null,
                                            'is_occupied' => $spot['is_occupied'] ?? false
                                        ];
                                    @endphp
                                    <g class="cursor-pointer transition-all duration-300" 
                                       @click="showDetails = true; selectedSpot = {{ json_encode($spotData) }}"
                                       data-id="{{ $spot['id'] }}">
                                        <rect 
                                            x="{{ $x }}" 
                                            y="{{ $y }}" 
                                            width="{{ $width }}" 
                                            height="{{ $height }}" 
                                            class="park-slot {{ $spot['is_occupied'] ? 'fill-red-500/20 stroke-red-400' : 'fill-green-500/10 stroke-green-400 hover:fill-green-500/20' }}"
                                            stroke-width="2"
                                            rx="4"
                                        />
                                        <text 
                                            x="{{ $x + ($width/2) }}" 
                                            y="{{ $y + ($height/2) }}" 
                                            class="text-sm fill-white font-medium select-none pointer-events-none"
                                            text-anchor="middle" 
                                            dominant-baseline="middle"
                                        >
                                            {{ $spot['number'] }}
                                        </text>
                                    </g>
                                @endforeach

                                <!-- Bejárat jelzés -->
                                <g transform="translate(1100, 250)">
                                    <!-- Felirat -->
                                    <text 
                                        x="500" 
                                        y="40" 
                                        class="text-2xl fill-red-400 font-bold tracking-wider"
                                        text-anchor="middle" 
                                        dominant-baseline="middle"
                                        transform="rotate(269, 500, 40)"
                                    >
                                        BEJÁRAT
                                        <animate 
                                            attributeName="opacity"
                                            values="0.7;1;0.7"
                                            dur="2s"
                                            repeatCount="indefinite"
                                        />
                                    </text>
                                    
                                    <!-- Modern nyíl -->
                                    <g transform="translate(560, 40) rotate(92)">
                                        <!-- Nyíl fő része -->
                                        <path 
                                            d="M0,-5 L-15,15 L-5,15 L-5,35 L5,35 L5,15 L15,15 Z" 
                                            class="fill-red-400/20 stroke-red-400" 
                                            stroke-width="2"
                                            stroke-linejoin="round"
                                        >
                                            <animate 
                                                attributeName="fill-opacity"
                                                values="0.2;0.4;0.2"
                                                dur="1.5s"
                                                repeatCount="indefinite"
                                            />
                                        </path>
                                        
                                        <!-- Pulzáló gyűrű -->
                                        <circle 
                                            cx="0" 
                                            cy="15" 
                                            r="20" 
                                            class="fill-none stroke-red-400/30"
                                            stroke-width="2"
                                            stroke-dasharray="60"
                                        >
                                            <animate
                                                attributeName="stroke-dashoffset"
                                                values="60;0"
                                                dur="2s"
                                                repeatCount="indefinite"
                                            />
                                            <animate
                                                attributeName="r"
                                                values="20;25;20"
                                                dur="2s"
                                                repeatCount="indefinite"
                                            />
                                        </circle>
                                    </g>
                                </g>
                            </svg>

                            <!-- Parkolóhely részletek ablak -->
                            <div 
                                x-show="selectedSpot"
                                class="fixed top-20 right-4 max-h-[calc(100vh-10rem)] w-64 bg-gray-800 shadow-2xl transform transition-transform duration-300 rounded-lg z-50"
                                :class="selectedSpot ? 'translate-x-0' : 'translate-x-full'"
                                @click.away="selectedSpot = null"
                            >
                                <div class="h-full overflow-y-auto">
                                    <div class="sticky top-0 bg-gray-800 p-3 border-b border-gray-700 flex items-center justify-between rounded-t-lg">
                                        <h3 class="text-base font-medium text-white">Parkolóhely részletek</h3>
                                        <button @click="selectedSpot = null" class="text-gray-400 hover:text-gray-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-3 space-y-2">
                                        <!-- Azonosító -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Azonosító</label>
                                            <div class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-md text-xs" x-text="selectedSpot?.id"></div>
                                        </div>
                                        <!-- Parkolóhely száma -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Parkolóhely száma</label>
                                            <div class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-md text-xs" x-text="selectedSpot?.number"></div>
                                        </div>
                                        <!-- Tulajdonos -->
                                        <div x-show="selectedSpot?.owner">
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Tulajdonos</label>
                                            <div class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-md text-xs" x-text="selectedSpot?.owner"></div>
                                        </div>
                                        <!-- Kezelő -->
                                        <div x-show="selectedSpot?.handled_by">
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Kezelő</label>
                                            <div class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-md text-xs" x-text="selectedSpot?.handled_by"></div>
                                        </div>
                                        <!-- Igénylés dátuma -->
                                        <div x-show="selectedSpot?.request_date">
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Igénylés dátuma</label>
                                            <div class="bg-blue-500/10 text-blue-400 px-2 py-1 rounded-md text-xs" x-text="selectedSpot?.request_date"></div>
                                        </div>
                                        <!-- Foglalt státusz -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 mb-1">Státusz</label>
                                            <div :class="selectedSpot?.is_occupied ? 'bg-red-500/10 text-red-400' : 'bg-green-500/10 text-green-400'" class="px-2 py-1 rounded-md text-xs">
                                                <span x-text="selectedSpot?.is_occupied ? 'Foglalt' : 'Szabad'"></span>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5" x-show="!selectedSpot?.is_occupied">
                                            <!-- Igénylés gomb -->
                                            <button 
                                                @click="requestSpot(selectedSpot?.id)"
                                                class="w-full bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-2.5 py-1 rounded-lg transition-all duration-300 flex items-center justify-center space-x-1.5 group"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                <span>Parkolóhely igénylése</span>
                                            </button>
                                        </div>
                                        <div class="space-y-1.5" x-show="selectedSpot?.is_occupied">
                                            <!-- Lemondás gomb -->
                                            <button 
                                                @click="removeParkingSpot(selectedSpot?.id)"
                                                class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 px-2.5 py-1 rounded-lg transition-all duration-300 flex items-center justify-center space-x-1.5 group"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>Levétel a parkolóhelyről</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @if(count($owned ?? []) > 0)
                    <div class="p-4 bg-gray-800 rounded-lg shadow-lg">
                        <h3 class="text-lg font-medium text-white mb-4">Jelenlegi parkolóhelyek</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($spots as $spot)
                                @if (in_array($spot['id'], $owned))
                                    <div class="bg-gray-700/50 rounded-lg p-2 flex items-center justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="text-sm text-gray-300">{{ $spot['number'] }}. parkolóhely</div>
                                            <div class="text-xs text-gray-400">{{ $spot['owner'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $spot['request_date'] }}</div>
                                        </div>
                                        <button 
                                            onclick="removeParkingSpot('{{ $spot['id'] }}')"
                                            class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            function requestParkingSpot(spotId) {
                if (!spotId) return;

                fetch('/api/parkings/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        spot_id: spotId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Hiba történt az igénylés során: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Hiba:', error);
                    alert('Hiba történt az igénylés során');
                });
            }

            function removeParkingSpot(spotId) {
                if (!confirm('Biztosan le szeretnéd mondani ezt a parkolóhelyet?')) return;

                fetch('/api/parkings/release', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        spot_id: spotId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Hiba történt a lemondás során: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Hiba:', error);
                    alert('Hiba történt a lemondás során');
                });
            }

            // Foglalt parkolóhelyek megjelölése
            document.addEventListener('DOMContentLoaded', function() {
                const reservedSpots = {!! json_encode($owned ?? []) !!};
                
                reservedSpots.forEach(spotId => {
                    const spotElement = document.querySelector(`g[data-id="${spotId}"] rect`);
                    if (spotElement) {
                        spotElement.classList.add('reserved');
                    }
                });
            });
        </script>
    </x-app-layout>
