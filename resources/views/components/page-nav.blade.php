@props(['title' => '', 'items' => []])

<div class="bg-gray-800/50 rounded-lg shadow-md mb-6">
    @if($title)
        <div class="px-4 py-3 border-b border-gray-700/50">
            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">{{ $title }}</h3>
        </div>
    @endif
    
    <nav class="p-2">
        <ul class="flex flex-wrap gap-2">
            @foreach($items as $item)
                <li>
                    <a href="{{ $item['url'] }}" 
                       class="flex items-center px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700/50 transition-colors {{ request()->url() === $item['url'] ? 'bg-blue-500/10 text-blue-400' : '' }}">
                        @if(isset($item['icon']))
                            <span class="mr-2">
                                {!! $item['icon'] !!}
                            </span>
                        @endif
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</div>
