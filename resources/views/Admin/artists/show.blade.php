@extends('Layouts.Admin.admin')

@section('title', 'Artist Details - ' . $artist->name)
@section('breadcrumb', 'Admin / Artists / ' . $artist->name)

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">Artist Details</h2>
    <div class="flex gap-2">
        <a href="{{ route('admin.artists.index') }}" class="admin-btn admin-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Artists
        </a>
        <button onclick="deleteArtist({{ $artist->id }})" class="admin-btn admin-btn-danger">
            <i class="fas fa-trash"></i>
            Delete Artist
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="admin-card lg:col-span-1">
        <div class="text-center mb-4">
            <img src="{{ $artist->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($artist->stage_name ?? $artist->name).'&background=random&size=200' }}" 
                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
            <h3 class="text-xl font-bold">{{ $artist->stage_name ?? $artist->name }}</h3>
            @if($artist->stage_name && $artist->name !== $artist->stage_name)
                <p class="text-gray-500 text-sm">Legal Name: {{ $artist->name }}</p>
            @endif
        </div>

        <div class="border-t pt-4 mt-4 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Artist ID:</span>
                <span class="font-medium">#{{ $artist->id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Joined:</span>
                <span class="font-medium">{{ $artist->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Last Updated:</span>
                <span class="font-medium">{{ $artist->updated_at->format('M d, Y') }}</span>
            </div>
            @if($artist->user_id)
            <div class="flex justify-between">
                <span class="text-gray-500">Linked User ID:</span>
                <span class="font-medium">#{{ $artist->user_id }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Contact & Details -->
    <div class="admin-card lg:col-span-2">
        <h3 class="text-lg font-bold mb-4 border-b pb-2">Contact Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                <p class="text-gray-900">{{ $artist->email ?? 'Not provided' }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                <p class="text-gray-900">{{ $artist->phone ?? 'Not provided' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">National ID</label>
                <p class="text-gray-900">{{ $artist->national_id ?? 'Not provided' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Bank Details</label>
                <p class="text-gray-900">{{ $artist->bank_details ?? 'Not provided' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">M-Pesa Paybill</label>
                <p class="text-gray-900">{{ $artist->mpesa_paybill ?? 'Not provided' }}</p>
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2">Social Media</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">
                    <i class="fab fa-instagram"></i> Instagram
                </label>
                <p class="text-gray-900">
                    @if($artist->instagram)
                        <a href="https://instagram.com/{{ $artist->instagram }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $artist->instagram }}
                        </a>
                    @else
                        Not provided
                    @endif
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">
                    <i class="fab fa-twitter"></i> Twitter
                </label>
                <p class="text-gray-900">
                    @if($artist->twitter)
                        <a href="https://twitter.com/{{ $artist->twitter }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $artist->twitter }}
                        </a>
                    @else
                        Not provided
                    @endif
                </p>
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2">Biography</h3>
        <div class="prose max-w-none">
            @if($artist->bio)
                <p class="text-gray-700 whitespace-pre-wrap">{{ $artist->bio }}</p>
            @else
                <p class="text-gray-400 italic">No biography provided.</p>
            @endif
        </div>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deleteArtist(artistId) {
    if (confirm('Are you sure you want to delete this artist? This cannot be undone.')) {
        const form = document.getElementById('delete-form');
        form.action = "{{ url(env('ADMIN_PATH', 'system-access') . '/artists') }}/" + artistId;
        form.submit();
    }
}
</script>
@endpush

@endsection