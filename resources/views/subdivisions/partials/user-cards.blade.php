@foreach ($users as $user)
    <div class="subdivision-card">
        <div class="subdivision-user-info">
            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="subdivision-user-avatar">
            <div class="subdivision-user-details">
                <h3 class="subdivision-user-name">{{ $user->charactername }}</h3>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($user->subdivisions as $subdivision)
                        <div class="subdivision-badge" 
                             style="--badge-color: {{ $subdivision->color }}20; --badge-text-color: {{ $subdivision->color }}">
                            {{ $subdivision->name }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('subdivisions.assign.update') }}" class="mt-4">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($subdivisions as $subdivision)
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" 
                               name="subdivisions[]" 
                               value="{{ $subdivision->id }}" 
                               id="subdivision-{{ $user->id }}-{{ $subdivision->id }}"
                               @if ($user->subdivisions->contains($subdivision)) checked @endif
                               class="rounded border-gray-700 text-blue-500 focus:ring-blue-500/50 bg-gray-900/50">
                        <label for="subdivision-{{ $user->id }}-{{ $subdivision->id }}" 
                               class="text-sm text-gray-300">
                            {{ $subdivision->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="subdivision-button">
                    <svg class="subdivision-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ __('Frissítés') }}</span>
                </button>
            </div>
        </form>
    </div>
@endforeach