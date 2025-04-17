<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-white text-white view-reports-padding">
                <p class="top5">Ugrás a táblához</p>
                <div class="d-flex justify-content-around">
                    <a href="#heti-statisztika">
                        <x-primary-button>
                            {{ __('Heti statisztika') }}
                        </x-primary-button>
                    </a>

                    <a href="#elozo-het">
                        <x-primary-button>
                            {{ __('Előző hét (lezárt)') }}
                        </x-primary-button>
                    </a>

                    <a href="#inaktivitasok">
                        <x-primary-button>
                            {{ __('Inaktivitások') }}
                        </x-primary-button>
                    </a>

                    <a href="#regisztralt-felhasznalok">
                        <x-primary-button>
                            {{ __('Regisztrált felhasználók') }}
                        </x-primary-button>
                    </a>

                    <a href="#admin-logok">
                        <x-primary-button>
                            {{ __('Admin logok') }}
                        </x-primary-button>
                    </a>
                    <a href="{{ route('ranks.index') }}">
    <x-primary-button>
        {{ __('Rangok kezelése') }}
    </x-primary-button>
</a>
</a>
     <a href="{{ route('subdivisions.index') }}">
    <x-primary-button>
        {{ __('Alosztályok kezelése') }}
    </x-primary-button>
</a>
<a href="{{ route('announcements.create') }}">
    <x-primary-button>
        {{ __('Kozlemenyek kezelése') }}
    </x-primary-button>
</a>
                </div>
            </div>
        </div>
    </div>
</div>