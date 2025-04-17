<div id="announcement-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <h3 class="text-lg font-medium text-white">{{ __('Új közlemény létrehozása') }}</h3>
                <button type="button" onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('announcements.store') }}" method="POST" class="p-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Közlemény címe') }}</label>
                        <input type="text" name="title" id="title"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg text-white text-sm px-3 py-2 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-300 mb-1">{{ __('Közlemény tartalma') }}</label>
                        <textarea name="content" id="content" rows="4"
                                  class="w-full bg-gray-700 border border-gray-600 rounded-lg text-white text-sm px-3 py-2 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                  required></textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeAnnouncementModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                        {{ __('Mégse') }}
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        {{ __('Létrehozás') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
