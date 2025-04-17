<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('WebDev Napló') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <!-- Flash message -->
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if($logs->isEmpty())
                <div class="p-4 rounded shadow bg-gray-800 dark:bg-gray-900 border border-gray-300 dark:border-gray-700">
                    <p class="text-gray-800 dark:text-gray-200">
                        Nincsenek naplózott események.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($logs as $log)
                        <div class="p-4 rounded shadow bg-gray-800 dark:bg-gray-900 border border-gray-300 dark:border-gray-700">
                            <!-- Log header -->
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 dark:text-gray-300 text-sm font-mono">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-500 text-xs italic">
                                    Log ID: #{{ $log->id }}
                                </span>
                            </div>

                            <!-- Log details -->
                            <div class="mt-2">
                                <p class="text-gray-800 dark:text-gray-200 font-semibold">
                                    {{ $log->action }}
                                </p>
                                <p class="text-gray-600 text-white mt-1">
                                    {{ $log->details }}
                                </p>
                            </div>

                            <!-- Delete button -->
                            <form action="{{ route('logs.destroy', $log) }}" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-500"
                                    onclick="return confirm('Biztosan törölni szeretnéd ezt a naplóbejegyzést?')"
                                >
                                    Törlés
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>