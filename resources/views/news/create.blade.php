<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Új hír létrehozása') }}
            </h2>
            <a 
                href="{{ route('news.index') }}" 
                class="bg-gray-700/50 hover:bg-gray-700/70 text-gray-400 hover:text-gray-300 px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Vissza a hírekhez</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <form 
                    action="{{ route('news.store') }}" 
                    method="POST"
                    x-data="{
                        type: 'info',
                        title: '',
                        content: '',
                        loading: false,
                        async submitForm(e) {
                            e.preventDefault();
                            this.loading = true;

                            try {
                                const response = await fetch(e.target.action, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        type: this.type,
                                        title: this.title,
                                        content: this.content
                                    })
                                });

                                if (!response.ok) throw new Error('Hiba történt a mentés során');

                                window.location.href = '{{ route('news.index') }}';
                            } catch (error) {
                                console.error('Hiba a mentés során:', error);
                                alert('Hiba történt a hír mentése során');
                            } finally {
                                this.loading = false;
                            }
                        }
                    }"
                    @submit.prevent="submitForm"
                    class="p-6 space-y-6"
                >
                    <!-- Típus választó -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Hír típusa</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="relative flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="radio" 
                                    name="type" 
                                    value="info" 
                                    x-model="type"
                                    class="sr-only peer"
                                >
                                <div class="w-4 h-4 border-2 border-blue-400 peer-checked:bg-blue-400 rounded-full transition-colors"></div>
                                <span class="text-gray-400 group-hover:text-gray-300">Információ</span>
                            </label>
                            <label class="relative flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="radio" 
                                    name="type" 
                                    value="warning" 
                                    x-model="type"
                                    class="sr-only peer"
                                >
                                <div class="w-4 h-4 border-2 border-yellow-400 peer-checked:bg-yellow-400 rounded-full transition-colors"></div>
                                <span class="text-gray-400 group-hover:text-gray-300">Weboldallal kapcsolatos</span>
                            </label>
                            <label class="relative flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="radio" 
                                    name="type" 
                                    value="danger" 
                                    x-model="type"
                                    class="sr-only peer"
                                >
                                <div class="w-4 h-4 border-2 border-red-400 peer-checked:bg-red-400 rounded-full transition-colors"></div>
                                <span class="text-gray-400 group-hover:text-gray-300">Fontos</span>
                            </label>
                        </div>
                    </div>

                    <!-- Cím -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-400 mb-2">Cím</label>
                        <input 
                            type="text" 
                            id="title"
                            x-model="title"
                            class="w-full bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-blue-400 text-white placeholder-gray-400"
                            placeholder="Add meg a hír címét..."
                            required
                        >
                    </div>

                    <!-- Tartalom -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-400 mb-2">Tartalom</label>
                        <textarea 
                            id="content"
                            x-model="content"
                            rows="6"
                            class="w-full bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-blue-400 text-white placeholder-gray-400 resize-none"
                            placeholder="Írd be a hír tartalmát..."
                            required
                        ></textarea>
                    </div>

                    <!-- Gombok -->
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a 
                            href="{{ route('news.index') }}"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
                        >
                            Mégse
                        </a>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-400 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading || !title || !content"
                        >
                            <span x-show="!loading">Mentés</span>
                            <template x-if="loading">
                                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
