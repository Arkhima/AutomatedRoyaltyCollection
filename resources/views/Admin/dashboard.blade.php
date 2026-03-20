@extends('Layouts.Admin.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Users Card -->
    <div class="admin-card accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Users</p>
                <h3 class="text-3xl font-bold">{{ $userCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-yellow-500 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-yellow-500 hover:text-yellow-600">
                View all users <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Artists Card -->
    <div class="admin-card accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Artists</p>
                <h3 class="text-3xl font-bold">{{ $artistCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-microphone-alt text-purple-500 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.artists.index') }}" class="text-sm text-yellow-500 hover:text-yellow-600">
                View all artists <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Tracks Card -->
    <div class="admin-card accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Tracks</p>
                <h3 class="text-3xl font-bold">{{ $trackCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-music text-blue-500 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.tracks.index') }}" class="text-sm text-yellow-500 hover:text-yellow-600">
                View all tracks <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="admin-card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Recent Users</h3>
            <a href="{{ route('admin.users.index') }}" class="text-yellow-500 hover:text-yellow-600 text-sm">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @php
                $recentUsers = App\Models\User::latest()->take(5)->get();
            @endphp
            @foreach($recentUsers as $user)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                         class="w-10 h-10 rounded-full"
                         alt="{{ $user->name }}">
                    <div>
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="admin-badge {{ $user->role ?? 'user' }}">
                    {{ ucfirst($user->role ?? 'user') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="admin-card">
        <h3 class="text-lg font-semibold mb-4">Quick Navigation</h3>
        <div class="grid grid-cols-1 gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center group-hover:bg-yellow-200">
                        <i class="fas fa-users text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">Manage Users</p>
                        <p class="text-sm text-gray-500">View and manage system users</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-500"></i>
            </a>

            <a href="{{ route('admin.artists.index') }}" 
               class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200">
                        <i class="fas fa-microphone-alt text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">Manage Artists</p>
                        <p class="text-sm text-gray-500">View and manage artists</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-500"></i>
            </a>

            <a href="{{ route('admin.tracks.index') }}" 
               class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200">
                        <i class="fas fa-music text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">Manage Tracks</p>
                        <p class="text-sm text-gray-500">View and manage music tracks</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-500"></i>
            </a>
        </div>
    </div>
</div>

<!-- System Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="admin-card">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-green-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">System Health</p>
                <p class="text-xl font-semibold text-green-500">● Active</p>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-database text-blue-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Database</p>
                <p class="text-xl font-semibold">Connected</p>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Last Backup</p>
                <p class="text-xl font-semibold">{{ now()->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection