<div>
    <h3 class="text-lg font-medium text-figyelmeztetes mb-4">{{ __('Figyelmeztetések') }}</h3>
    @if($figyelmeztetesek->isEmpty())
        <p class="text-gray-400">{{ __('Nincsenek figyelmeztetések') }}</p>
    @else
        <ul class="space-y-4">
            @foreach($figyelmeztetesek as $figyelmeztetes)
                <li class="warning-box figyelmeztetes">
                    <p><strong>{{ __('Indok:') }}</strong> {{ $figyelmeztetes->reason }}</p>
                    <p><strong>{{ __('Pontok:') }}</strong> {{ $figyelmeztetes->points }}</p>
                    <p><strong>{{ __('Kiadta:') }}</strong> {{ $figyelmeztetes->admin->charactername }}</p>
                    <p><strong>{{ __('Dátum:') }}</strong> {{ \Carbon\Carbon::parse($figyelmeztetes->created_at)->format('Y-m-d H:i') }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>