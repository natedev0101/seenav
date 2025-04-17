<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Új közlemény létrehozása') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <form method="POST" action="{{ route('announcements.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Cím mező -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300">
                                {{ __('Cím') }}
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   required
                                   class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ old('title') }}"
                                   placeholder="{{ __('Add meg a közlemény címét...') }}">
                            @error('title')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tartalom mező -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-300">
                                {{ __('Tartalom') }}
                            </label>
                            <div class="mt-1 mb-4 flex flex-wrap gap-2">
                                @foreach(['[b][/b]' => 'B', '[i][/i]' => 'I', '[u][/u]' => 'U', '[url=][/url]' => 'URL', '[img][/img]' => 'IMG', '[quote][/quote]' => 'Quote'] as $code => $label)
                                    <button type="button" 
                                            onclick="insertBBCode('{{ $code }}')"
                                            class="bg-gray-700 hover:bg-gray-600 text-gray-300 px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="mb-4 grid grid-cols-8 gap-2">
                                @foreach(['😀', '😂', '😍', '😎', '👍', '🎉', '🔥', '💯', '🥳', '😡', '😢', '🤔', '❤️', '👏', '🙌', '😅', '🎁', '💡', '📢'] as $emoji)
                                    <button type="button" 
                                            onclick="insertEmoji('{{ $emoji }}')"
                                            class="bg-gray-700 hover:bg-gray-600 text-2xl p-2 rounded-md transition-colors">
                                        {{ $emoji }}
                                    </button>
                                @endforeach
                            </div>
                            <textarea name="content" 
                                      id="content" 
                                      rows="8" 
                                      required
                                      class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="{{ __('Írd be a közlemény tartalmát...') }}">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Létrehozás gomb -->
                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                {{ __('Közlemény létrehozása') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script>
        function insertBBCode(tag) {
            const contentField = document.getElementById('content');
            const startPos = contentField.selectionStart;
            const endPos = contentField.selectionEnd;
            const selectedText = contentField.value.substring(startPos, endPos);
            
            // Ha van kijelölt szöveg, akkor azt tesszük a BB kód közé
            if (selectedText) {
                const openTag = tag.split('][')[0] + ']';
                const closeTag = '[' + tag.split('][')[1];
                contentField.value = contentField.value.substring(0, startPos) + 
                                   openTag + selectedText + closeTag + 
                                   contentField.value.substring(endPos);
            } else {
                // Ha nincs kijelölt szöveg, akkor csak beszúrjuk a BB kódot
                contentField.value = contentField.value.substring(0, startPos) + 
                                   tag + 
                                   contentField.value.substring(endPos);
                
                // A kurzort a BB kód közé helyezzük
                const newCursorPos = startPos + tag.split('][')[0].length + 1;
                contentField.setSelectionRange(newCursorPos, newCursorPos);
            }
            
            contentField.focus();
        }

        function insertEmoji(emoji) {
            const contentField = document.getElementById('content');
            const startPos = contentField.selectionStart;
            const text = contentField.value;
            contentField.value = text.substring(0, startPos) + emoji + text.substring(startPos);
            
            // A kurzort az emoji után helyezzük
            const newCursorPos = startPos + emoji.length;
            contentField.setSelectionRange(newCursorPos, newCursorPos);
            
            contentField.focus();
        }
    </script>
</x-app-layout>