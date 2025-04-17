<div>
    <h3 class="text-lg font-medium text-minusz_pont mb-4">{{ __('Mínusz pontok') }}</h3>
    @if($minuszPontok->isEmpty())
        <p class="text-gray-400">{{ __('Nincsenek mínusz pontok') }}</p>
    @else
        <ul class="space-y-4">
            @foreach($minuszPontok as $minuszPont)
                <li class="warning-box minusz_pont">
                    <p><strong>{{ __('Indok:') }}</strong> {{ $minuszPont->reason }}</p>
                    <p><strong>{{ __('Pontok:') }}</strong> {{ $minuszPont->points }}</p>
                    <p><strong>{{ __('Kiadta:') }}</strong> {{ $minuszPont->admin->charactername }}</p>
                    <p><strong>{{ __('Dátum:') }}</strong> {{ \Carbon\Carbon::parse($minuszPont->created_at)->format('Y-m-d H:i') }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>