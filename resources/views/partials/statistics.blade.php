@php
$users = \App\Models\User::all();
@endphp

<div class="p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-800/50 rounded-lg overflow-hidden">
            <thead class="bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Név</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Karakter Név</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Telefonszám</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jelvényszám</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Játszott Idő</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Utolsó Aktív</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Státusz</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->charactername }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->phone_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->badge_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->getFormattedTimeSpentAttribute() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ optional($user->rank)->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->last_active ? $user->last_active->diffForHumans() : 'Soha' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->getIsOnlineAttribute() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->getIsOnlineAttribute() ? 'Online' : 'Offline' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
