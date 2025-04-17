<div id="createAnnouncementModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            {{ __('Új közlemény létrehozása') }}
                        </h3>
                        <form id="createAnnouncementForm" class="mt-4" onsubmit="createAnnouncement(event)">
                            @csrf
                            <div class="mb-4">
                                <label for="createTitle" class="block text-sm font-medium text-gray-300">{{ __('Cím') }}</label>
                                <input type="text" name="title" id="createTitle" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-600 rounded-md bg-gray-700 text-white" required>
                            </div>
                            <div class="mb-4">
                                <label for="createContent" class="block text-sm font-medium text-gray-300">{{ __('Tartalom') }}</label>
                                <div class="mt-1 mb-4 flex flex-wrap gap-2">
                                    @foreach(['[b][/b]', '[i][/i]', '[u][/u]', '[url=][/url]', '[img][/img]', '[quote][/quote]'] as $code)
                                        <button type="button" onclick="insertBBCode('createContent', '{{ $code }}')" 
                                                class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                                            {{ str_replace(['[', ']', '/'], '', explode('=', $code)[0]) }}
                                        </button>
                                    @endforeach
                                </div>
                                <div class="mb-4 grid grid-cols-8 gap-2">
                                    @foreach(['😀', '😂', '😍', '😎', '👍', '🎉', '🔥', '💯', '🥳', '😡', '😢', '🤔', '❤️', '👏', '🙌', '😅', '🎁', '💡', '📢'] as $emoji)
                                        <button type="button" onclick="insertEmoji('createContent', '{{ $emoji }}')"
                                                class="bg-gray-700 hover:bg-gray-600 text-2xl p-2 rounded-md transition-colors">
                                            {{ $emoji }}
                                        </button>
                                    @endforeach
                                </div>
                                <textarea name="content" id="createContent" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-600 rounded-md bg-gray-700 text-white" required></textarea>
                            </div>
                            <div class="bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ __('Létrehozás') }}
                                </button>
                                <button type="button" onclick="closeCreateModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ __('Mégse') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            </div>
        </div>
    </div>
</div>

<script>
function insertBBCode(targetId, code) {
    const textarea = document.getElementById(targetId);
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selectedText = text.substring(start, end);
    
    let insertText;
    if (code.includes('=]')) {
        const url = prompt('{{ __("Add meg a linket:") }}', 'https://');
        if (url === null) return;
        insertText = code.replace('=]', '=' + url + ']') + selectedText + '[/url]';
    } else {
        const openTag = code.substring(0, code.indexOf('[/'));
        const closeTag = code.substring(code.indexOf('[/'));
        insertText = openTag + selectedText + closeTag;
    }
    
    textarea.value = text.substring(0, start) + insertText + text.substring(end);
    textarea.focus();
    const newCursorPos = start + insertText.length;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
}

function insertEmoji(targetId, emoji) {
    const textarea = document.getElementById(targetId);
    const start = textarea.selectionStart;
    const text = textarea.value;
    
    textarea.value = text.substring(0, start) + emoji + text.substring(start);
    textarea.focus();
    const newCursorPos = start + emoji.length;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
}
</script>
