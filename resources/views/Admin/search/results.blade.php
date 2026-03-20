@extends('layouts.admin')

@section('title', 'Search Results')
@section('breadcrumb', 'Admin / Search')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold brand-font mb-2">Search Results for "{{ $query }}"</h2>
    <p class="text-gray-500">Found {{ $users->count() + $artists->count() + $tracks->count() }} results</p>
</div>

@if($users->count() > 0 || $artists->count() > 0 || $tracks->count() > 0)
    <!-- Users Results -->
    @if($users->count() > 0)
    <div class="admin-card mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-users text-yellow-500"></i>
            <h3 class="text-lg font-semibold">Users ({{ $users->count() }})</h3>
        </div>
        <div class="space-y-3">
            @foreach($users as $user)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-yellow-50 transition">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                         class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium">
                            <a href="{{ route('Admin.Users.edit', $user) ?? '#' }}" class="hover:text-yellow-500">
                                {{ $user->name }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">ID: {{ $user->id }} • {{ $user->email }}</p>
                    </div>
                </div>
                <span class="admin-badge {{ $user->role ?? 'user' }}">
                    {{ ucfirst($user->role ?? 'user') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Artists Results -->
    @if($artists->count() > 0)
    <div class="admin-card mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-microphone-alt text-purple-500"></i>
            <h3 class="text-lg font-semibold">Artists ({{ $artists->count() }})</h3>
        </div>
        <div class="space-y-3">
            @foreach($artists as $artist)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-yellow-50 transition">
                <div class="flex items-center gap-3">
                    @if($artist->photo)
                        <img src="{{ $artist->photo }}" class="w-10 h-10 rounded-full">
                    @else
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-purple-500"></i>
                        </div>
                    @endif
                    <div>
                        <p class="font-medium">
                            <a href="{{ route('Admin.Artists.edit', $artist) ?? '#' }}" class="hover:text-yellow-500">
                                {{ $artist->name }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">
                            ID: {{ $artist->id }} • 
                            @if($artist->stage_name)
                                Stage: {{ $artist->stage_name }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tracks Results -->
    @if($tracks->count() > 0)
    <div class="admin-card mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-music text-blue-500"></i>
            <h3 class="text-lg font-semibold">Tracks ({{ $tracks->count() }})</h3>
        </div>
        <div class="space-y-3">
            @foreach($tracks as $track)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-yellow-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-music text-blue-500"></i>
                    </div>
                    <div>
                        <p class="font-medium">
                            <a href="{{ route('Admin.Tracks.edit', $track) ?? '#' }}" class="hover:text-yellow-500">
                                {{ $track->title }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">
                            ID: {{ $track->id }} • 
                            Artist: {{ $track->artist->name ?? 'Unknown' }} • 
                            Genre: {{ $track->genre ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@else
    <!-- No Results -->
    <div class="admin-card text-center py-12">
        <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-500 mb-2">No results found</h3>
        <p class="text-gray-400">Try searching with different keywords</p>
    </div>
@endif

<!-- Quick Search Tips -->
<div class="admin-card mt-6 bg-yellow-50">
    <div class="flex items-start gap-3">
        <i class="fas fa-lightbulb text-yellow-500 mt-1"></i>
        <div>
            <h4 class="font-semibold mb-2">Search Tips:</h4>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Search by ID: Try "123" to find specific items</li>
                <li>• Search by name: "John" finds users/artists named John</li>
                <li>• Search by email: "gmail.com" finds all Gmail users</li>
                <li>• Search by track title: "Love" finds songs with "Love" in the title</li>
                <li>• Search by genre: "Rock" finds all rock tracks</li>
            </ul>
        </div>
    </div>
</div>
@endsection