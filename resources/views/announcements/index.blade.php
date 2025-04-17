<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Közlemények') }}
            </h2>
            @if(Auth::user()->is_superadmin || Auth::user()->isAdmin)
                <a href="{{ route('announcements.create') }}" 
                   class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse($announcements as $announcement)
                    <div class="announcement-card-bg rounded-lg p-4 relative group">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                @if($announcement->creator->profile_picture)
                                    <img src="{{ asset('storage/' . Str::replace('public/', '', $announcement->creator->profile_picture)) }}"
                                         class="w-10 h-10 rounded-lg object-cover border-2 border-gray-700"
                                         alt="{{ $announcement->creator->charactername }}"
                                         onerror="this.onerror=null; this.src='{{ asset('img/default-profile.png') }}';">
                                @else
                                    <img src="{{ asset('img/default-profile.png') }}"
                                         class="w-10 h-10 rounded-lg object-cover border-2 border-gray-700"
                                         alt="{{ $announcement->creator->charactername }}">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="announcement-title-bg rounded-t-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-xl font-medium text-white">{{ $announcement->title }}</h3>
                                        @if(Auth::user()->is_superadmin || Auth::user()->isAdmin)
                                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button onclick="editAnnouncement('{{ $announcement->id }}', '{{ addslashes($announcement->title) }}', {{ json_encode($announcement->content) }})" 
                                                        class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors"
                                                            onclick="return confirm('{{ __('Biztosan törölni szeretnéd ezt a közleményt?') }}')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="announcement-content-bg rounded-b-lg p-4">
                                    <div class="text-gray-300 whitespace-pre-wrap break-words">{{ $announcement->content }}</div>
                                    <div class="flex items-center gap-2 mt-4 text-sm">
                                        <span class="announcement-author">{{ $announcement->creator->charactername }}</span>
                                        <span class="text-gray-500">•</span>
                                        <span class="announcement-time" title="{{ $announcement->created_at->format('Y.m.d H:i') }}">
                                            {{ $announcement->created_at->diffForHumans() }}
                                        </span>
                                        @if($announcement->updated_at && $announcement->updated_at->ne($announcement->created_at))
                                            <span class="announcement-edited">
                                                (szerkesztve: {{ $announcement->updated_at->format('Y.m.d H:i') }})
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-300">{{ __('Nincsenek közlemények') }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ __('Még nem lett létrehozva egyetlen közlemény sem.') }}</p>
                        @if(Auth::user()->is_superadmin || Auth::user()->isAdmin)
                            <div class="mt-6">
                                <a href="{{ route('announcements.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    {{ __('Új közlemény létrehozása') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse

                @if($announcements->hasPages())
                    <div class="mt-4">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Közlemény szerkesztése modal -->
    <div id="announcement-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="announcement-card-bg rounded-lg shadow-xl max-w-2xl w-full mx-auto">
                <div class="announcement-title-bg flex items-center justify-between p-4 rounded-t-lg border-b border-gray-600">
                    <h3 class="text-lg font-medium text-white">{{ __('Közlemény szerkesztése') }}</h3>
                    <button onclick="toggleEditModal()" class="text-gray-400 hover:text-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="announcement-edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="announcement-content-bg p-4 space-y-4">
                        <div>
                            <label for="edit-title" class="block text-sm font-medium text-gray-300">{{ __('Cím') }}</label>
                            <input type="text" name="title" id="edit-title" required
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="edit-content" class="block text-sm font-medium text-gray-300">{{ __('Tartalom') }}</label>
                            <textarea name="content" id="edit-content" rows="4" required
                                      class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 border-t border-gray-600">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            {{ __('Mentés') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleEditModal() {
            const modal = document.getElementById('announcement-edit-modal');
            modal.classList.toggle('hidden');
            document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
        }

        function editAnnouncement(id, title, content) {
            const modal = document.getElementById('announcement-edit-modal');
            document.getElementById('announcement-edit-form').action = `/announcements/${id}`;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-content').value = content;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close modal when clicking outside
        document.getElementById('announcement-edit-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('announcement-edit-modal');
                if (!modal.classList.contains('hidden')) {
                    toggleEditModal();
                }
            }
        });
    </script>
</x-app-layout>
