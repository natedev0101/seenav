<x-app-layout>
    @if(auth()->user()->isAdmin || auth()->user()->is_superadmin)
        <div class="min-h-screen bg-gray-900">
            @include('leader-navigation')

            <!-- Fő tartalom -->
            <div x-data="{ activeTab: '' }" @tab-changed.window="activeTab = $event.detail.tab">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-gray-800/50 overflow-hidden shadow-xl rounded-lg">
                            <!-- Felhasználók statisztika -->
                            <div x-show="activeTab === 'users'" x-cloak>
                                @include('partials.statistics')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-red-500/10 text-red-400 p-4 rounded-lg">
                    <p>Nincs jogosultságod az oldal megtekintéséhez!</p>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
