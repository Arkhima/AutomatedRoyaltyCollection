@extends('Layouts.Admin.admin')

@section('title', 'User Details - ' . $user->name)
@section('breadcrumb', 'Admin / Users / ' . $user->name)

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">User Details</h2>
    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back to Users
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- User Profile Card -->
    <div class="admin-card">
        <div class="flex items-center gap-4 mb-4">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&size=128' }}" 
                 class="w-24 h-24 rounded-full">
            <div>
                <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                <p class="text-gray-600">{{ $user->email }}</p>
                <div class="flex gap-2 mt-2">
                    <span class="admin-badge 
                        @if($user->role == 'admin') bg-purple-100 text-purple-800 
                        @elseif($user->role == 'artist') bg-blue-100 text-blue-800 
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($user->role ?? 'user') }}
                    </span>
                    <span class="admin-badge 
                        @if($user->status == 'active') bg-green-100 text-green-800 
                        @elseif($user->status == 'pending') bg-yellow-100 text-yellow-800 
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($user->status ?? 'inactive') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="border-t pt-4 mt-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">User ID:</span>
                    <span class="font-medium">#{{ $user->id }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Joined:</span>
                    <span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Email Verified:</span>
                    <span class="font-medium">{{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Last Updated:</span>
                    <span class="font-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    <div class="admin-card">
        <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="w-full">
                @csrf
                <button type="submit" 
                        class="admin-btn w-full {{ $user->status === 'active' ? 'admin-btn-warning' : 'admin-btn-success' }}"
                        onclick="return confirm('Are you sure you want to {{ $user->status === 'active' ? 'deactivate' : 'activate' }} this user?')">
                    <i class="fas {{ $user->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                    {{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}
                </button>
            </form>

            <button onclick="deleteUser({{ $user->id }})" 
                    class="admin-btn admin-btn-danger w-full">
                <i class="fas fa-trash"></i>
                Delete User
            </button>
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
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This cannot be undone.')) {
        const form = document.getElementById('delete-form');
        form.action = "{{ url(env('ADMIN_PATH', 'system-access') . '/users') }}/" + userId;
        form.submit();
    }
}
</script>
@endpush

@endsection