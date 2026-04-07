@extends('Layouts.Admin.admin')

@section('title', 'Tracks Management')
@section('breadcrumb', 'Admin / Tracks')

@section('content')

<!-- Flash Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">Manage Tracks</h2>
    <div class="flex gap-2">
        <a href="{{ route('admin.tracks.export.csv') }}" class="admin-btn admin-btn-success">
            <i class="fas fa-file-csv"></i>
            Export CSV
        </a>
        <a href="{{ route('admin.tracks.export.pdf') }}" class="admin-btn admin-btn-danger">
            <i class="fas fa-file-pdf"></i>
            Export PDF
        </a>
        <a href="{{ route('admin.tracks.create') }}" class="admin-btn admin-btn-primary">
            <i class="fas fa-plus"></i>
            Add Track
        </a>
    </div>
</div>

<!-- Tracks Table -->
<div class="admin-card overflow-hidden">
    <div class="table-container">
        <table class="admin-table min-w-full">
            <thead>
                <tr>
                    <th class="text-left">Track</th>
                    <th class="text-left">Artist</th>
                    <th class="text-left">ISRC</th>
                    <th class="text-right">Rate/Stream</th>
                    <th class="text-right">Streams</th>
                    <th class="text-center">Status</th>
                    <th class="text-left">Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tracks as $track)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $track->cover_art ?? 'https://ui-avatars.com/api/?name='.urlencode($track->title).'&background=random' }}" 
                                     class="w-10 h-10 rounded object-cover">
                                <div>
                                    <div class="font-medium">{{ $track->title }}</div>
                                    @if($track->album)
                                        <div class="text-xs text-gray-500">{{ $track->album }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $track->artist->name ?? '—' }}</td>
                        <td class="font-mono text-sm">{{ $track->isrc_code ?? '—' }}</td>
                        <td class="text-right">${{ number_format($track->rate_per_stream ?? 0, 4) }}</td>
                        <td class="text-right">{{ number_format($track->total_streams ?? 0) }}</td>
                        <td class="text-center">
                            <span class="admin-badge {{ $track->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($track->status ?? 'active') }}
                            </span>
                        </td>
                        <td>{{ $track->created_at->format('M d, Y') }}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.tracks.show', $track) }}" 
                                   class="admin-btn admin-btn-secondary admin-btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="deleteTrack({{ $track->id }})" 
                                        class="admin-btn admin-btn-danger admin-btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            No tracks found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $tracks->links() }}
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deleteTrack(trackId) {
    if (confirm('Are you sure you want to delete this track?')) {
        const form = document.getElementById('delete-form');
        form.action = "{{ url(env('ADMIN_PATH', 'system-access') . '/tracks') }}/" + trackId;
        form.submit();
    }
}
</script>
@endpush

@endsection