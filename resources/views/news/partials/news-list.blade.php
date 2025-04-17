@if($news->isEmpty())
    <div class="text-center py-12">
        <div class="bg-gray-800/50 rounded-lg p-8 inline-block">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-3.5-3.5A2 2 0 0012.586 4H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-400 text-lg">Nincsenek {{ request()->boolean('archived') ? 'archivált' : 'aktuális' }} hírek</p>
        </div>
    </div>
@else
    <div class="flex flex-col gap-4 max-w-4xl mx-auto">
        @foreach($news as $item)
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 hover:bg-white/20 transition-all duration-300 border-2 w-full min-h-[200px]
                @if($item->type === 'info') border-blue-500/50 hover:border-blue-500 
                @elseif($item->type === 'warning') border-yellow-500/50 hover:border-yellow-500
                @elseif($item->type === 'danger') border-red-500/50 hover:border-red-500
                @else border-green-500/50 hover:border-green-500 @endif" 
                data-news-id="{{ $item->id }}"
            >
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-lg">
                            @php
                                $nameParts = explode(' ', $item->creator->charactername ?? 'Ismeretlen');
                                $initials = '';
                                if (count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($nameParts[0], 0, 2));
                                }
                            @endphp
                            {{ $initials }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm text-gray-300" title="Létrehozta">
                                {{ $item->creator->charactername ?? $item->creator->name }}
                            </div>
                            <div class="text-xs text-gray-500 bg-gray-800/50 px-2 py-1 rounded-md inline-flex items-center" title="Létrehozva">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $item->created_at->format('Y.m.d H:i:s') }}
                            </div>
                        </div>
                    </div>

                    <!-- Gombok -->
                    <div class="flex items-center gap-2">
                        @if(!$item->archived_at && Auth::user()->isAdmin)
                            <form action="{{ route('news.archive', $item) }}" method="POST" class="inline">
                                @csrf
                                <button type="button" 
                                        class="bg-gray-500/10 hover:bg-gray-500/20 text-gray-400 hover:text-gray-300 p-1.5 rounded-lg transition-colors" 
                                        title="Archiválás"
                                        onclick="if(confirm('Biztosan archiválni szeretnéd ezt a hírt?')) this.closest('form').submit();">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </button>
                            </form>

                            <button type="button" 
                                    class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors"
                                    title="Szerkesztés"
                                    onclick="editNews('{{ $item->id }}', '{{ addslashes($item->title) }}', '{{ addslashes($item->content) }}', '{{ $item->type }}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        @endif

                        @if(Auth::user()->isAdmin)
                            <form action="{{ route('news.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors"
                                        title="Törlés"
                                        onclick="if(confirm('Biztosan törölni szeretnéd ezt a hírt? Ez a művelet nem visszavonható!')) this.closest('form').submit();">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="flex-grow">
                    <div class="bg-gray-900/30 rounded-lg p-4 mb-4">
                        <h3 class="text-xl font-semibold text-gray-200 text-center" 
                            data-original-title="{{ $item->title }}">
                            {{ $item->title }}
                        </h3>
                    </div>
                    
                    <div class="bg-gray-900/20 rounded-lg p-4">
                        <p class="whitespace-pre-wrap text-gray-300">{{ $item->content }}</p>
                    </div>
                </div>

                @if($item->archived_at)
                    <div class="mt-6 pt-4 border-t border-gray-700">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center text-white font-semibold text-lg">
                                @php
                                    $archiveNameParts = explode(' ', $item->archivedBy->charactername ?? 'Archivált');
                                    $archiveInitials = '';
                                    if (count($archiveNameParts) >= 2) {
                                        $archiveInitials = strtoupper(substr($archiveNameParts[0], 0, 1) . substr($archiveNameParts[1], 0, 1));
                                    } else {
                                        $archiveInitials = strtoupper(substr($archiveNameParts[0], 0, 2));
                                    }
                                @endphp
                                {{ $archiveInitials }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-gray-300" title="Archiválta">
                                    {{ $item->archivedBy->charactername ?? $item->archivedBy->name }}
                                </div>
                                <div class="text-xs text-gray-500 bg-gray-800/50 px-2 py-1 rounded-md inline-flex items-center" title="Archiválva">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                    {{ $item->archived_at->format('Y.m.d H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Szerkesztő Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-gray-800 text-white border-gray-700">
            <div class="modal-header border-gray-700">
                <h5 class="modal-title text-xl" id="editNewsModalLabel">Hír szerkesztése</h5>
                <button type="button" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors" data-bs-dismiss="modal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="editNewsForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="editTitle" class="block text-sm font-medium text-gray-300 mb-1">Cím</label>
                        <input type="text" id="editTitle" name="title" class="w-full bg-gray-900/50 border-0 border-b border-gray-700 focus:border-blue-500 focus:ring-0 rounded-lg text-white">
                    </div>
                    <div class="mb-4">
                        <label for="editContent" class="block text-sm font-medium text-gray-300 mb-1">Tartalom</label>
                        <textarea id="editContent" name="content" rows="6" class="w-full bg-gray-900/50 border-0 border-b border-gray-700 focus:border-blue-500 focus:ring-0 rounded-lg text-white"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="editType" class="block text-sm font-medium text-gray-300 mb-1">Típus</label>
                        <select id="editType" name="type" class="w-full bg-gray-900/50 border-0 border-b border-gray-700 focus:border-blue-500 focus:ring-0 rounded-lg text-white">
                            <option value="info">Általános</option>
                            <option value="warning">Weboldallal kapcsolatos</option>
                            <option value="danger">Fontos</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-gray-700 flex justify-end gap-3">
                <button type="button" class="px-4 py-2 bg-gray-700/50 hover:bg-gray-700 text-gray-300 rounded-lg transition-colors" data-bs-dismiss="modal">Mégsem</button>
                <button type="button" onclick="saveNews()" class="px-4 py-2 bg-blue-500/80 hover:bg-blue-500 text-white rounded-lg transition-colors">Mentés</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentNewsId = null;
const editModal = new bootstrap.Modal(document.getElementById('editNewsModal'));

function decodeHtmlEntities(text) {
    const textarea = document.createElement('textarea');
    textarea.innerHTML = text;
    return textarea.value;
}

function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function editNews(id, title, content, type) {
    currentNewsId = id;
    
    // HTML entitások és speciális karakterek dekódolása
    const decodedTitle = decodeHtmlEntities(title);
    const decodedContent = decodeHtmlEntities(content);
    
    // Mezők kitöltése
    document.getElementById('editTitle').value = decodedTitle;
    document.getElementById('editContent').value = decodedContent;
    document.getElementById('editType').value = type;
    
    // Modál címének frissítése
    document.getElementById('editNewsModalLabel').textContent = `Hír szerkesztése: ${decodedTitle}`;
    
    editModal.show();
}

function saveNews() {
    const form = document.getElementById('editNewsForm');
    const formData = new FormData(form);
    formData.append('_method', 'PUT');

    fetch(`/news/${currentNewsId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Hiba történt a mentés során');
            });
        }
        return response.json();
    })
    .then(data => {
        editModal.hide();
        showNotification('Hír sikeresen frissítve!');
        setTimeout(() => { 
            window.location.reload(); 
        }, 1000);
    })
    .catch(error => {
        console.error('Hiba:', error);
        showNotification(error.message || 'Hiba történt a mentés során!', 'error');
    });
}
</script>
@endpush
