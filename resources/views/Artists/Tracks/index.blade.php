@extends('layouts.Artists.Dashboard')

@section('content')

<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-music text-purple-500 mr-3"></i>
                My Music Catalog
            </h1>
            <p class="text-gray-600 mt-1">Manage your tracks</p>
        </div>
          <a href="{{ route('Artists.Tracks.create') }}"
              class="btn-primary-custom bg-gradient-to-r from-purple-600 to-indigo-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
              <i class="fas fa-plus-circle mr-3 text-lg"></i>
              <span class="uppercase tracking-widest">Add New Track</span>
          </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="catalog-card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-headphones mr-2"></i> Title
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i> Artist
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-barcode mr-2"></i> ISRC Code
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-coins mr-2"></i> Rate/Stream
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i> Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tracks as $track)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-music text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $track->title }}</div>
                                    <div class="text-sm muted">{{ $track->genre ?? 'Various' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $track->artist->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono text-gray-900">{{ $track->isrc_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Ksh. {{ number_format($track->rate_per_stream, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('Artists.Tracks.edit', $track->id) }}" 
                                   class="inline-flex items-center px-3 py-2 rounded-md bg-white border text-indigo-600 hover:bg-indigo-50 shadow-sm">
                                    <i class="fas fa-edit mr-2"></i>
                                    <span class="text-sm">Edit</span>
                                </a>
                                <form method="POST" action="{{ route('Artists.Tracks.destroy', $track->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this track?')"
                                            class="inline-flex items-center px-3 py-2 rounded-md bg-white border text-red-600 hover:bg-red-50 shadow-sm">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        <span class="text-sm">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">
                            <div class="bg-white rounded-xl shadow-md overflow-hidden text-center py-12">
                                <i class="fas fa-music text-5xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No tracks found</h3>
                                <p class="text-gray-500 mb-6">You haven't uploaded any tracks yet</p>
                                <a href="{{ route('Artists.Tracks.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200">
                                   <i class="fas fa-plus-circle mr-2"></i>
                                   Add Your First Track
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tracks instanceof \Illuminate\Pagination\LengthAwarePaginator && $tracks->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $tracks->links() }}
        </div>
        @endif
    </div>
</div>

@endsection