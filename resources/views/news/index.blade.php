<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Hírek
                </h2>
                <div class="flex space-x-2">
                    <button type="button" 
                            class="news-filter-btn bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors {{ !request()->boolean('archived') ? 'bg-blue-500/30' : '' }}"
                            data-archived="0">
                        Aktuális
                    </button>
                    <button type="button" 
                            class="news-filter-btn bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-colors {{ request()->boolean('archived') ? 'bg-blue-500/30' : '' }}"
                            data-archived="1">
                        Elavult
                    </button>
                </div>
            </div>
            @if(Auth::user()->isAdmin || Auth::user()->is_superadmin)
                <a href="{{ route('news.create') }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="news-list" class="mt-6">
                @include('news.partials.news-list')
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newsListContainer = document.getElementById('news-list');
            const filterButtons = document.querySelectorAll('.news-filter-btn');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Aktív állapot kezelése
                    filterButtons.forEach(btn => btn.classList.remove('bg-blue-500/30'));
                    this.classList.add('bg-blue-500/30');

                    // AJAX kérés
                    fetch(`{{ route('news.index') }}?archived=${this.dataset.archived}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        newsListContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Hiba történt:', error);
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
