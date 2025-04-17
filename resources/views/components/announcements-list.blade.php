<div id="announcement-list">
    @foreach($announcements as $announcement)
        <div class="bg-gray-700 p-4 rounded-lg shadow-md mb-4">
            <h3 class="text-white font-medium">{{ $announcement->title }}</h3>
            <p class="text-gray-300 mt-2">{!! $announcement->content !!}</p>
            <p class="text-sm text-gray-400 mt-2"><strong>Készítette:</strong> {{ $announcement->creator->charactername }}</p>
        </div>
    @endforeach

    <!-- Lapozás -->
    <div class="mt-4">
    @if($announcements instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $announcements->links() }}
@endif

    </div>
</div>
