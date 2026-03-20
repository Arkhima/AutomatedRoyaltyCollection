@extends('Layouts.Admin.admin')

@section('title', 'User Management')
@section('breadcrumb', 'Admin / Users')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">Manage Users</h2>
    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i>
        Add New User
    </a>
</div>

<!-- Filters -->
<div class="admin-card mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Name or email..." 
                   class="admin-input">
        </div>
        <div class="w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" class="admin-input">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="artist" {{ request('role') == 'artist' ? 'selected' : '' }}>Artist</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        <div class="w-48">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="admin-input">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="admin-btn admin-btn-secondary">
            <i class="fas fa-filter"></i>
            Filter
        </button>
        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
            <i class="fas fa-redo"></i>
            Reset
        </a>
    </form>
</div>

<!-- Users Table -->
<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                             class="w-10 h-10 rounded-full">
                        <span class="font-medium">{{ $user->name }}</span>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="admin-badge {{ $user->role ?? 'user' }}">
                        {{ ucfirst($user->role ?? 'user') }}
                    </span>
                </td>
                <td>
                    <span class="admin-badge {{ $user->status ?? 'active' }}">
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.index', $user) }}" 
                           class="admin-btn admin-btn-secondary admin-btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteUser({{ $user->id }})" 
                                class="admin-btn admin-btn-danger admin-btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    No users found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination">
    @if($users->onFirstPage())
        <span class="pagination-item disabled">◀ Previous</span>
    @else
        <a href="{{ $users->previousPageUrl() }}" class="pagination-item">◀ Previous</a>
    @endif

    @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
        @if($page == $users->currentPage())
            <span class="pagination-item active">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
        @endif
    @endforeach

    @if($users->hasMorePages())
        <a href="{{ $users->nextPageUrl() }}" class="pagination-item">Next ▶</a>
    @else
        <span class="pagination-item disabled">Next ▶</span>
    @endif
</div>

<!-- Delete Form (hidden) -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/users/${userId}`;
        form.submit();
    }
}
</script>
@endpush
@endsection