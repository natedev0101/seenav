<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Névváltás kérelmezése') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-white">
                    <!-- Aktuális kérelem állapota -->
                    @if(isset($currentRequest))
                        <div class="mb-6 p-4 rounded-lg {{ $currentRequest->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : ($currentRequest->status === 'approved' ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400') }}">
                            <div class="flex items-center space-x-2">
                                @if($currentRequest->status === 'pending')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>A névváltási kérelmed elbírálás alatt van.</span>
                                @elseif($currentRequest->status === 'approved')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>A névváltási kérelmed elfogadásra került!</span>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span>A névváltási kérelmed elutasításra került.</span>
                                @endif
                            </div>
                            @if($currentRequest->admin_comment)
                                <p class="mt-2 text-sm">Admin megjegyzés: {{ $currentRequest->admin_comment }}</p>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('name-change.submit') }}" class="space-y-6">
                        @csrf

                        <!-- Jelenlegi név -->
                        <div>
                            <x-input-label for="current_name" :value="__('Jelenlegi név')" />
                            <div class="mt-2 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="top: 50%; transform: translateY(-50%);">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <x-text-input 
                                    id="current_name" 
                                    type="text" 
                                    name="current_name" 
                                    :value="auth()->user()->charactername"
                                    disabled
                                    class="bg-gray-700 text-gray-300 pl-10 w-full"
                                    style="padding-left: 2.5rem;"
                                />
                            </div>
                        </div>

                        <!-- Kért új név -->
                        <div>
                            <x-input-label for="requested_name" :value="__('Kért új név')" />
                            <div class="mt-2 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" style="top: 50%; transform: translateY(-50%);">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                                <x-text-input 
                                    id="requested_name" 
                                    type="text" 
                                    name="requested_name" 
                                    :value="old('requested_name')"
                                    required 
                                    autofocus
                                    class="bg-gray-700 w-full"
                                    style="padding-left: 2.5rem;"
                                />
                            </div>
                            <p class="mt-1 text-sm text-gray-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                A névnek 3-30 karakter hosszúnak kell lennie és egyedinek.
                            </p>
                            @error('requested_name')
                                <p class="mt-1 text-sm text-red-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Indoklás -->
                        <div>
                            <x-input-label for="reason" :value="__('Indoklás')" />
                            <div class="mt-2 relative">
                                <div class="absolute left-0 pl-3" style="top: 0.75rem;">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                                <textarea
                                    id="reason"
                                    name="reason"
                                    rows="4"
                                    required
                                    class="w-full rounded-md border-0 bg-gray-700 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm"
                                    style="padding-left: 2.5rem;"
                                >{{ old('reason') }}</textarea>
                            </div>
                            <p class="mt-1 text-sm text-gray-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Kérjük, indokold meg részletesen a névváltási kérelmedet (minimum 10 karakter).
                            </p>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Szabályok -->
                        <div class="rounded-lg bg-gray-700/50 p-4 text-sm text-gray-300">
                            <h3 class="font-medium text-white mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Fontos tudnivalók:
                            </h3>
                            <ul class="list-none space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Egy időben csak egy aktív névváltási kérelmed lehet.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>A kért névnek egyedinek kell lennie.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>A névváltási kérelmet az adminok bírálják el.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Az elutasított kérelem után új kérelmet adhatsz be.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>A korábbi neveid megőrzésre kerülnek.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Kérelem beküldése') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
