@if($announcements->isEmpty())
    <p class="text-gray-400 text-center">{{ __('Nincsenek közlemények.') }}</p>
@else
    @foreach($announcements as $announcement)
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-4">
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-semibold text-white">{{ $announcement->title }}</h3>
                    @if(auth()->user()->is_admin || auth()->user()->is_superadmin)
                        <div class="flex space-x-2">
                            <button onclick="showEditModal({{ $announcement->id }})" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteAnnouncement({{ $announcement->id }})" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="text-sm text-gray-400 mt-1">
                    {{ __('Közzétéve:') }} {{ $announcement->created_at->format('Y.m.d H:i') }}
                    {{ __('szerző:') }} <a href="{{ route('users.show', $announcement->creator->id) }}" class="text-blue-400 hover:text-blue-300">{{ $announcement->creator->charactername }}</a>
                </div>
                <div class="mt-4 text-gray-300 space-y-4 announcement-content">
                    {!! BBCode::parse($announcement->content) !!}
                </div>
            </div>
        </div>
    @endforeach

    @if(method_exists($announcements, 'links'))
        <div class="mt-4">
            {{ $announcements->links() }}
        </div>
    @endif
@endif
