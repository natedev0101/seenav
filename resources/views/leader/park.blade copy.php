<x-app-layout>
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
            <div class="p-2 bg-gray-700/30 rounded-lg">
                <svg id="park-map" viewBox="0 0 1920 1080" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                    <!-- Háttérkép -->
                    <image href="{{ asset('images/park.png') }}" x="0" y="0" width="1920" height="1080"/>

                    <!-- Parkolóhelyek -->
                    @foreach(config('parking.spots') as $spot)
                        @php
                            // Koordináták átszámítása az 1920x1080-as viewportra
                            $x = ($spot['position']['x'] / 1200) * 1920;
                            $y = ($spot['position']['y'] / 500) * 1080;
                            $width = (70 / 1200) * 1920; // 40 pixel szélesség átszámítva
                            $height = (20 / 500) * 1080; // 30 pixel magasság átszámítva
                            $rotation = $spot['rotation'] ?? 0;
                            // Ha függőleges, akkor megcseréljük a szélességet és magasságot
                            if ($rotation == 90) {
                                $temp = $width;
                                $width = $height;
                                $height = $temp;
                            }
                        @endphp
                        <g class="cursor-pointer transition-all duration-300" 
                           @click="$dispatch('open-modal', { spot: {{ json_encode($spot) }} })"
                           data-id="{{ $spot['id'] }}">
                            <rect 
                                x="{{ $x }}" 
                                y="{{ $y }}" 
                                width="{{ $width }}" 
                                height="{{ $height }}" 
                                class="park-slot fill-green-500/10 stroke-green-400 hover:fill-green-500/20"
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
            @if(count($owned ?? []) > 0)
                <div class="p-4 bg-gray-800 rounded-lg shadow-lg">
                    <h3 class="text-lg font-medium text-white mb-4">Jelenlegi parkolóhelyek</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($owned as $ownedSpot)
                            <div class="relative group">
                                <div class="bg-blue-500/10 text-blue-400 px-4 py-2 rounded-lg">
                                    {{ $ownedSpot }}
                                </div>
                                <button 
                                    onclick="deleteParkingSpot({{ $ownedSpot }})"
                                    class="absolute -top-2 -right-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal a parkolóhely részleteihez -->
    <div
        x-data="{ 
            show: false,
            spot: null
        }"
        @open-modal.window="
            show = true;
            spot = $event.detail.spot;
        "
        @click.away="show = false"
        @keydown.escape.window="show = false"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            
            <div class="relative bg-gray-800 rounded-lg max-w-lg w-full p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-white" x-text="'Parkolóhely #' + spot?.number"></h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Azonosító:</span>
                        <span class="text-white" x-text="spot?.id"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Státusz:</span>
                        <span class="text-green-400">Szabad</span>
                    </div>
                    
                    <button 
                        @click="requestParkingSpot(spot?.id)"
                        class="w-full bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors mt-4"
                    >
                        Igénylés
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function requestParkingSpot(spotId) {
            if (!spotId) return;

            fetch('/parkolokiosztas/igenyel', {
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

        function deleteParkingSpot(spotId) {
            if (!confirm('Biztosan le szeretnéd mondani ezt a parkolóhelyet?')) return;

            fetch('/parkolokiosztas/lemond', {
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
