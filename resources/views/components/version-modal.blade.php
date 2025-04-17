@props(['show' => false])

<div x-data="{ show: @js($show) }" @keydown.escape.window="show = false">
    <div @click="show = true" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="show" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900 opacity-75"></div>

            <div class="relative bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-medium text-white">Verzió kiválasztása</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($versions as $version)
                        <button wire:click="selectVersion({{ $version->id }})" 
                                class="w-full text-left p-3 rounded-lg transition-colors
                                       bg-{{ $version->color }}-500/10 hover:bg-{{ $version->color }}-500/20 
                                       text-{{ $version->color }}-400 hover:text-{{ $version->color }}-300
                                       {{ $version->is_active ? 'ring-2 ring-' . $version->color . '-500' : '' }}">
                            {{ $version->version }}
                            @if($version->is_active)
                                <span class="float-right text-{{ $version->color }}-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>