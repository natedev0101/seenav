<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Névváltási kérelmek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Fülek -->
                <div class="name-change-tabs flex space-x-4 mb-6">
                    <button onclick="showTab('pending')" class="name-change-tab flex items-center space-x-2 px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600" data-tab="pending">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Függőben ({{ $pendingRequests->count() }})</span>
                    </button>
                    <button onclick="showTab('approved')" class="name-change-tab flex items-center space-x-2 px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600" data-tab="approved">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Elfogadott ({{ $approvedRequests->count() }})</span>
                    </button>
                    <button onclick="showTab('rejected')" class="name-change-tab flex items-center space-x-2 px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600" data-tab="rejected">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Elutasított ({{ $rejectedRequests->count() }})</span>
                    </button>
                </div>

                <!-- Tartalom konténer -->
                <div id="tab-contents">
                    <!-- Függőben lévő kérelmek -->
                    <div id="pending-content" style="display: block;">
                        @if($pendingRequests->isEmpty())
                            <p class="text-gray-400 text-center py-8">Nincsenek függőben lévő kérelmek.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Felhasználó</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jelenlegi név</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kért név</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Indoklás</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kérelem ideje</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Műveletek</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach($pendingRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->user->username }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->current_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->requested_name }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-300">{{ $request->reason }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->created_at->format('Y.m.d H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                    <button onclick="openProcessModal('{{ $request->id }}')" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 p-1.5 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Elfogadott kérelmek -->
                    <div id="approved-content" style="display: none;">
                        @if($approvedRequests->isEmpty())
                            <p class="text-gray-400 text-center py-8">Nincsenek elfogadott kérelmek.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Felhasználó</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Régi név</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Új név</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Elfogadva</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Elfogadta</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Megjegyzés</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach($approvedRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->user->username }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->current_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->requested_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->processed_at->format('Y.m.d H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->processor->username }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-300">{{ $request->admin_comment }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Elutasított kérelmek -->
                    <div id="rejected-content" style="display: none;">
                        @if($rejectedRequests->isEmpty())
                            <p class="text-gray-400 text-center py-8">Nincsenek elutasított kérelmek.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Felhasználó</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kért név</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Elutasítva</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Elutasította</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Indok</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach($rejectedRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->user->username }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->requested_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->processed_at->format('Y.m.d H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->processor->username }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-300">{{ $request->admin_comment }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="processModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-lg w-full mx-4">
            <h3 class="text-xl font-semibold text-white mb-4">Kérelem elbírálása</h3>
            <form id="processForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Státusz</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="approved" class="form-radio text-blue-500">
                            <span class="ml-2 text-gray-300">Elfogadás</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="rejected" class="form-radio text-red-500">
                            <span class="ml-2 text-gray-300">Elutasítás</span>
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="admin_comment" class="block text-gray-300 mb-2">Admin megjegyzés</label>
                    <textarea name="admin_comment" id="admin_comment" rows="3" class="w-full bg-gray-700 text-gray-300 rounded-lg p-2"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeProcessModal()" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg">
                        Mégse
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                        Mentés
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Fülek aktiválása/deaktiválása
            document.querySelectorAll('.name-change-tab').forEach(tab => {
                if (tab.getAttribute('data-tab') === tabName) {
                    tab.classList.add('bg-blue-500', 'text-white');
                    tab.classList.remove('bg-gray-700', 'text-gray-300');
                } else {
                    tab.classList.remove('bg-blue-500', 'text-white');
                    tab.classList.add('bg-gray-700', 'text-gray-300');
                }
            });
            
            // Tartalom mutatása/elrejtése
            document.getElementById('pending-content').style.display = 'none';
            document.getElementById('approved-content').style.display = 'none';
            document.getElementById('rejected-content').style.display = 'none';
            document.getElementById(tabName + '-content').style.display = 'block';
        }

        function openProcessModal(requestId) {
            const modal = document.getElementById('processModal');
            const form = document.getElementById('processForm');
            
            form.action = `/nevvaltas/${requestId}/feldolgozas`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.querySelector('input[name="status"][value="approved"]').checked = true;
        }

        function closeProcessModal() {
            const modal = document.getElementById('processModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('processForm').reset();
        }

        // Oldal betöltésekor a pending tab megjelenítése
        document.addEventListener('DOMContentLoaded', function() {
            showTab('pending');
        });
    </script>
</x-app-layout>
