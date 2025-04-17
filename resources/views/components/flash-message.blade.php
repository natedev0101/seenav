<div>
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-md shadow-sm">
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-md shadow-sm">
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
@endif
</div>