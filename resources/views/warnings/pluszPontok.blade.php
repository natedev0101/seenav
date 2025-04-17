<div>
    <h3 class="text-lg font-medium text-plusz_pont mb-4">{{ __('Plusz pontok') }}</h3>
    @if($pluszPontok->isEmpty())
        <p class="text-gray-400">{{ __('Nincsenek plusz pontok') }}</p>
    @else
        <ul class="space-y-4">
            @foreach($pluszPontok as $pluszPont)
                <li class="warning-box plusz_pont">
                    <p><strong>{{ __('Indok:') }}</strong> {{ $pluszPont->reason }}</p>
                    <p><strong>{{ __('Pontok:') }}</strong> {{ $pluszPont->points }}</p>
                    <p><strong>{{ __('Kiadta:') }}</strong> {{ $pluszPont->admin->charactername }}</p>
                    <p><strong>{{ __('Dátum:') }}</strong> {{ \Carbon\Carbon::parse($pluszPont->created_at)->format('Y-m-d H:i') }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>