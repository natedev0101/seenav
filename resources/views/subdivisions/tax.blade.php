@php
    use Carbon\Carbon;

    $activeTab = session('activeTab', 'week');
    $currentDate = Carbon::now();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Adóigazgatóság') }}
        </h2>
    </x-slot>
    <x-slot name="headerIcon">
        <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-6 h-6 text-green-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    </x-slot>

    <!-- Panel & Menu Buttons -->

    <div class="p-4 sm:p-6"
        x-data="{ 
            detailOpen: false,
            weekOpen: {{ $activeTab === 'week' ? 'true' : 'false' }}, 
            monthOpen: {{ $activeTab === 'month' ? 'true' : 'false' }}, 
            adminOpen: {{ $activeTab === 'admin' ? 'true' : 'false' }} 
        }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-gray-700/30 flex flex-col sm:flex-row flex-wrap items-center justify-center gap-2 p-2 border-b border-gray-700/50 shadow-lg">

                    <form action="{{ route('tax.handler', ['type' => 'week']) }}" method="POST">
                        @csrf

                        <button type="submit" @click="weekOpen = true, monthOpen = false, adminOpen = false, detailOpen = false" class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                        style="background-color: rgba(0, 97, 243, 0.35); border: 2px solid rgb(0, 74, 185)">
                            {{ __('Heti adózású cégek') }}
                        </button>
                    </form>

                    <form action="{{ route('tax.handler', ['type' => 'month']) }}" method="POST">
                        @csrf

                        <button type="submit" @click="monthOpen = true, weekOpen = false, adminOpen = false, detailOpen = false" class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                        style="background-color: rgba(10, 173, 37, 0.35); border: 2px solid rgb(6, 129, 26)">
                            {{ __('Havi adózású cégek') }}
                        </button>
                    </form>

                    <form action="{{ route('tax.handler', ['type' => 'administrator']) }}" method="POST">
                        @csrf

                        <button type="submit" @click="adminOpen = true, monthOpen = false, weekOpen = false, detailOpen = false" class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                        style="background-color: rgba(126, 126, 126, 0.35); border: 2px solid rgb(126, 126, 126)">
                            {{ __('Ügyintézések') }}
                        </button>
                    </form>

                    <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                    style="background-color: rgba(248, 120, 0, 0.35); border: 2px solid rgb(201, 97, 0)">
                        {{ __('Adóbeszedések') }}
                    </button>

                    <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                    style="background-color: rgba(21, 248, 0, 0.35); border: 2px solid rgb(15, 177, 0)">
                        {{ __('Árak') }}
                    </button>

                    <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                    style="background-color: rgba(0, 149, 248, 0.35); border: 2px solid rgb(0, 149, 248)">
                        {{ __('Rendszámok') }}
                    </button>

                    <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                    style="background-color: rgba(255, 251, 0, 0.40); border: 2px solid rgb(255, 251, 0)">
                        {{ __('Lezárt ingatlanok') }}
                    </button>

                    <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-white px-3 md:px-10 py-2 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base" 
                    style="background-color: rgba(63, 37, 155, 0.35); border: 2px solid rgb(63, 37, 155)">
                        {{ __('Alkalmazottak') }}
                    </button>
                </div>

                <!-- Search & Manage Buttons -->

                <div class="m-2 flex items-center gap-1">
                    <div class="w-full h-full bg-gray-700/60 p-1 rounded-lg">
                        <input class="bg-gray-800/50 border-none h-7 text-white w-full rounded-lg" type="text" placeholder="Keresés ...">
                    </div>

                    <button class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-1 md:px-1 py-2.5 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base"
                    style="border: 2px solid rgba(1, 79, 158, 0.53)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                    </button> 

                    <button class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-1 md:px-1 py-2.5 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base"
                    style="border: 2px solid rgba(1, 79, 158, 0.53)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </button> 

                    <button class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-1 md:px-1 py-2.5 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base"
                    style="border: 2px solid rgba(1, 79, 158, 0.53)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button> 
                </div>

                <!-- Detail Panel -->

                <div x-show="detailOpen" 
                class="absolute rounded-lg top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-2"
                style="background-color: rgba(20, 37, 85, 0.85);  border: 2px solid rgba(1, 80, 158, 0.90)">
                    <div class="w-full flex items-center">
                        <div class="w-full flex items-center gap-1">
                            <svg class="text-green-400 size-6 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <span class="text-white uppercase font-semibold">{{ __('Kezelés') }}</span>
                        </div>

                        <div class="w-full flex justify-end">
                            <button @click="detailOpen = false, getClosestData(event, 'del')" class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-red-400 hover:text-red-300 px-1 md:px-1 py-2.5 md:py-1 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base"
                            style="background-color: rgba(158, 1, 1, 0.53);  border: 2px solid rgba(158, 1, 1, 0.86)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <hr class="bg-gray-50 mt-1.5 ml-1 mr-1" style="padding: 0.2%">

                    <div id="detailBody" class="w-full mt-1"></div>

                    <hr class="bg-gray-50 mt-1.5 ml-1 mr-1" style="padding: 0.2%">

                    <div class="w-full flex items-center gap-2 mt-2">
                        <div>
                            <input type="checkbox" class="rounded focus:ring-blue-600 shadow-sm border-gray-300 bg-gray-800/50">
                            <span class="text-yellow-400">{{ __('Felszólítva') }}</span>
                        </div>
                        <div>
                            <input type="checkbox" class="rounded focus:ring-blue-600 shadow-sm border-gray-300 bg-gray-800/50">
                            <span class="text-red-500">{{ __('Adócsalás') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">{{ __('Dátum:') }}</span>
                            <input type="date" class="rounded bg-gray-800/50 text-white h-8">
                        </div>
                    </div>

                    <div class="w-full mt-2 mb-2">
                        <input class="w-full bg-gray-800/50 text-white rounded-lg" type="text" placeholder="Megjegyzés ...">
                    </div>

                    <hr class="bg-gray-50 mt-1.5 ml-1 mr-1" style="padding: 0.3%">

                    <div class="w-full flex items-center gap-2 mt-1">
                        <div>
                            <input type="checkbox" class="rounded focus:ring-blue-600 shadow-sm border-gray-300 bg-gray-800/50">
                            <span class="text-red-500">{{ __('Bezárás') }}</span>
                        </div>
                        <div>
                            <input type="checkbox" class="rounded focus:ring-blue-600 shadow-sm border-gray-300 bg-gray-800/50">
                            <span class="text-red-300">{{ __('IDG. Bezárás') }}</span>
                        </div>
                    </div>

                    <div class="w-full mt-2 mb-2">
                        <input class="w-full bg-gray-800/50 text-white rounded-lg" type="text" placeholder="Megjegyzés ...">
                    </div>

                    <div class="w-full flex items-center justify-center mt-2">
                        <button class="w-full sm:w-auto bg-gradient-to-r from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-20 md:px-20 py-2.5 md:py-1 rounded-lg transition-all duration-200 flex items-center gap-1 justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base"
                        style="border: 2px solid rgba(1, 79, 158, 0.53)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>

                            {{ __('Mentés') }}
                        </button>
                    </div>
                </div>

                <!-- Tables -->

                <div x-show="weekOpen" class="p-1 m-1">
                    <table class="w-full table-auto">
                        <thead class="text-center text-xs lg:text-sm text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="bg-gray-700/50">{{ __('Nyilv. sz.') }}</th> 
                                <th class="bg-gray-700/50">{{ __('Adószám') }}</th>
                                <th class="bg-gray-700/50">{{ __('Cégnév') }}</th>
                                <th class="bg-gray-700/50">{{ __('Tevékenység') }}</th>
                                <th class="bg-gray-700/50">{{ __('Tulajdonos') }}</th>
                                <th class="bg-gray-700/50">{{ __('Fórum') }}</th>
                                <th class="bg-gray-700/50">{{ __('Adó') }}</th>
                                <th class="bg-gray-700/50 p-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900/30 text-center text-white">
                            @if (isset($weekData) && $weekData->isNotEmpty())
                                @foreach ($weekData as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->taxnumber }}</td>
                                        <td>{{ $data->companyname }}</td>
                                        <td>{{ $data->activity }} [<span class="text-red-600">{{ $data->marking }}</span>]</td>
                                        <td>{{ $data->owner }} (( <span class="text-gray-400">{{ $data->charid }}</span> )) </td>
                                        <td><a class="text-blue-600" href="{{ $data->forum }}" target="_blank">Link</a></td>

                                        @if (Carbon::parse($data->tax)->isBefore($currentDate))
                                            <td class="text-red-600">{{ Carbon::parse($data->tax)->format('Y.m.d') }}</td>
                                        @else 
                                            <td>{{ Carbon::parse($data->tax)->format('Y.m.d') }}</td>
                                        @endif

                                        <td id="manageBtn">
                                            <form action="{{ route('tax.handler', ['type' => 'data']) }}" method="POST">
                                                @csrf

                                                <button type="submit" @click="detailOpen = true, getClosestData(event, 'get')" 
                                                class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-0.6 md:px-1 py-2.5 md:py-0.5 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base m-1">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                                    </svg>
                                                </button> 
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div x-show="monthOpen" class="p-1 m-1">
                    <table class="w-full table-auto">
                        <thead class="text-center text-xs lg:text-sm text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="bg-gray-700/50">{{ __('Nyilv. sz.') }}</th> 
                                <th class="bg-gray-700/50">{{ __('Adószám') }}</th>
                                <th class="bg-gray-700/50">{{ __('Cégnév') }}</th>
                                <th class="bg-gray-700/50">{{ __('Tevékenység') }}</th>
                                <th class="bg-gray-700/50">{{ __('Interior') }}</th>
                                <th class="bg-gray-700/50">{{ __('Tulajdonos') }}</th>
                                <th class="bg-gray-700/50">{{ __('Fórum') }}</th>
                                <th class="bg-gray-700/50">{{ __('Adó') }}</th>
                                <th class="bg-gray-700/50 p-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900/30 text-center text-white">
                            @if (isset($monthData) && $monthData->isNotEmpty())
                                @foreach ($monthData as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->taxnumber }}</td>
                                        <td>{{ $data->companyname }}</td>
                                        <td>{{ $data->activity }} [<span class="text-red-600">{{ $data->marking }}</span>]</td>
                                        <td>{{ $data->interiorid }}</td>
                                        <td>{{ $data->owner }} (( <span class="text-gray-400">{{ $data->charid }}</span> )) </td>
                                        <td><a class="text-blue-600" href="{{ $data->forum }}" target="_blank">Link</a></td>

                                        @if (Carbon::parse($data->tax)->isBefore($currentDate))
                                            <td class="text-red-600">{{ Carbon::parse($data->tax)->format('Y.m.d') }}</td>
                                        @else 
                                            <td>{{ Carbon::parse($data->tax)->format('Y.m.d') }}</td>
                                        @endif

                                        <td>
                                            <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-0.6 md:px-1 py-2.5 md:py-0.5 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base m-1">
                                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                                </svg>
                                            </button> 
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div x-show="adminOpen" class="p-1 m-1">
                    <table class="w-full table-auto">
                        <thead class="text-center text-xs lg:text-sm text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="bg-gray-700/50">{{ __('Nyilv. sz.') }}</th> 
                                <th class="bg-gray-700/50">{{ __('Adószám') }}</th>
                                <th class="bg-gray-700/50">{{ __('Cégnév') }}</th>
                                <th class="bg-gray-700/50">{{ __('Összeg') }}</th>
                                <th class="bg-gray-700/50">{{ __('Indok') }}</th>
                                <th class="bg-gray-700/50">{{ __('Ügyintéző') }}</th>
                                <th class="bg-gray-700/50">{{ __('Bizonyíték') }}</th>
                                <th class="bg-gray-700/50">{{ __('Dátum') }}</th>
                                <th class="bg-gray-700/50 p-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-900/30 text-center text-white">
                            @if (isset($adminData) && $adminData->isNotEmpty())
                                @foreach ($adminData as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->taxnumber }}</td>
                                        <td>{{ $data->companyname }}</td>
                                        <td><span class="text-green-600">{{ number_format($data->amount, 0, ',', '.') }} $</span></td>
                                        <td>{{ $data->reason }}</td>
                                        <td>{{ $data->administrator }}</td>
                                        <td><a class="text-blue-600" href="{{ $data->proof }}" target="_blank">Link</a></td>
                                        <td>{{ Carbon::parse($data->date)->format('Y.m.d') }}</td>

                                        <td>
                                            <button class="w-full sm:w-auto hover:from-blue-500/20 hover:to-blue-600/20 text-blue-400 hover:text-blue-300 px-0.6 md:px-1 py-2.5 md:py-0.5 rounded-lg transition-all duration-200 flex items-center justify-center sm:justify-start space-x-2 shadow-sm text-sm md:text-base m-1">
                                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                                </svg>
                                            </button> 
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getClosestData(event, type) {
            const tableData = event.target.closest('tr');
            const detalBody = document.getElementById('detailBody');

            let components = [];

            if (type == "get") {       
                if (tableData) {
                    const child = tableData.children;

                    Array.from(child).forEach((data) => {
                        if (data.innerHTML) {
                            if (data.id != "manageBtn") {
                                components.push(data.innerHTML);  
                            }
                        }
                    });
                }

                if (components.length > 0) {
                    components.forEach((data) => {
                        const div = document.createElement('div');
                        div.classList.add('w-full', 'flex', 'justify-start', 'gap-2', 'mt-1');
                        detalBody.appendChild(div);

                        const span = document.createElement('span');
                        span.classList.add('text-white');
                        span.innerHTML = data;
                        div.appendChild(span);

                        const button = document.createElement('button');
                        button.classList.add(
                            'w-full', 'sm:w-auto', 'bg-gradient-to-r', 'from-blue-500/10', 'to-blue-600/10', 
                            'hover:from-blue-500/20', 'hover:to-blue-600/20', 'text-blue-400', 'hover:text-blue-300', 
                            'px-0.6', 'md:px-0.5', 'py-2.5', 'md:py-0.5', 'rounded-lg', 'transition-all', 
                            'duration-200', 'flex', 'items-center', 'justify-center', 'sm:justify-start', 'space-x-2', 
                            'shadow-sm', 'text-sm', 'md:text-base'
                        );

                        button.style.border = "2px solid rgba(1, 79, 158, 0.53)";

                        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                        svg.setAttribute("class", "w-4 h-4 text-blue-400");
                        svg.setAttribute("fill", "none");
                        svg.setAttribute("stroke", "currentColor");
                        svg.setAttribute("viewBox", "0 0 24 24");

                        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                        path.setAttribute("stroke-linecap", "round");
                        path.setAttribute("stroke-linejoin", "round");
                        path.setAttribute("stroke-width", "2");
                        path.setAttribute("d", "M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10");

                        svg.appendChild(path);
                        button.appendChild(svg);

                        div.appendChild(button);
                    });
                }
            } else if (type == "del") {
                const detailBodyChild = detalBody.children;

                if (detailBodyChild) {
                    Array.from(detailBodyChild).forEach((data) => {
                        data.remove();
                    });
                }
            }
        }
    </script>
</x-app-layout>