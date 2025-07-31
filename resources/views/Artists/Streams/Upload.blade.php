@extends('layouts.Artists.Dashboard')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Upload Streaming Data</h2>
    
    <form action="{{ route('Artists.Streams.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        
        <div>
            <label for="usage_file" class="block text-sm font-medium text-gray-700">CSV File</label>
            <input type="file" name="usage_file" id="usage_file" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   accept=".csv,.txt" required>
            <p class="mt-1 text-sm text-gray-500">Upload a CSV file containing streaming data</p>
        </div>
        
        <div>
            <button type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Upload File
            </button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('Artists.Streams.download-template') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm">
            Download CSV Template
        </a>
    </div>
</div>
@endsection