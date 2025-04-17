<div>
    @if($show)
    <div class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5 z-50">
        <div class="max-w-screen-xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="p-2 rounded-lg bg-gray-800 shadow-lg sm:p-3">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex-1 flex items-center">
                        <div class="flex p-2 rounded-lg bg-gray-700">
                            <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="ml-3 font-medium text-white">
                            <span class="md:hidden">
                                Ez az oldal cookie-kat használ.
                            </span>
                            <span class="hidden md:inline">
                                Ez az oldal cookie-kat használ a megfelelő működés és a felhasználói élmény javítása érdekében.
                            </span>
                        </p>
                    </div>
                    <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto sm:ml-4">
                        <div class="flex space-x-4">
                            <button wire:click="accept" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Elfogadom
                            </button>
                            <button wire:click="decline" class="flex items-center justify-center px-4 py-2 border border-gray-700 text-sm leading-5 font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Elutasítom
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
