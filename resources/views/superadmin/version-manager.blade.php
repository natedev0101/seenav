<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Verziókezelés
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <!-- Új verzió form -->
                    <form method="POST" action="{{ route('admin.version.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="version" value="Verzió szám" />
                                <x-input id="version" type="text" name="version" required class="mt-1 block w-full bg-gray-700 border-gray-600 text-white" placeholder="pl. 1.0.1" />
                            </div>
                            <div>
                                <x-label for="title" value="Frissítés címe" />
                                <x-input id="title" type="text" name="title" required class="mt-1 block w-full bg-gray-700 border-gray-600 text-white" placeholder="pl. Új funkciók hozzáadása" />
                            </div>
                        </div>
                        <div>
                            <x-label for="description" value="Frissítés leírása" />
                            <textarea id="description" name="description" required rows="4" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500" placeholder="Részletes leírás a változtatásokról..."></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_current" name="is_current" class="rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                            <label for="is_current" class="ml-2 text-sm text-gray-300">
                                Beállítás aktuális verzióként
                            </label>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Új verzió</span>
                            </button>
                        </div>
                    </form>

                    <!-- Verziók listája -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-white mb-4">Korábbi verziók</h3>
                        <div class="space-y-4">
                            @foreach($versions as $version)
                                <div class="bg-gray-700/30 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="text-lg font-medium text-white">{{ $version->title }}</h4>
                                            <span class="text-sm text-blue-400">v{{ $version->version }}</span>
                                            @if($version->is_current)
                                                <span class="bg-green-500/10 text-green-400 px-2 py-0.5 rounded text-xs">
                                                    Aktuális
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <form method="POST" action="{{ route('admin.version.set-current', $version) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.version.destroy', $version) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 p-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-gray-300 text-sm whitespace-pre-line">{{ $version->description }}</p>
                                    <div class="mt-2 text-xs text-gray-400">
                                        {{ $version->created_at->format('Y.m.d. H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
