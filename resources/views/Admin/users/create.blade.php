@extends('Layouts.Admin.admin')

@section('title', 'Create User')
@section('breadcrumb', 'Admin / Users / Create')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold brand-font">Create New User</h2>
    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back to Users
    </a>
</div>

<!-- Validation Errors -->
@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Create User Form -->
<div class="admin-card max-w-2xl">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        
        <!-- Name -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="name">
                Full Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                   class="admin-input w-full" placeholder="Enter full name" required>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                Email Address <span class="text-red-500">*</span>
            </label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                   class="admin-input w-full" placeholder="Enter email address" required>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="password">
                Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password" id="password" 
                   class="admin-input w-full" placeholder="Enter password" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">
                Confirm Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                   class="admin-input w-full" placeholder="Confirm password" required>
        </div>

        <!-- Role -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="role">
                Role <span class="text-red-500">*</span>
            </label>
            <select name="role" id="role" class="admin-input w-full" required>
                <option value="">Select Role</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="artist" {{ old('role') == 'artist' ? 'selected' : '' }}>Artist</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="status">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status" id="status" class="admin-input w-full" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-3">
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fas fa-save"></i>
                Create User
            </button>
            <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection