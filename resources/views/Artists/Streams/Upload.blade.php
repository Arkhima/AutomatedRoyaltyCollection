@extends('layouts.Artists.Dashboard')

@section('content')
<div class="max-w-2xl mx-auto bg-gradient-to-br from-white to-blue-50 p-8 rounded-2xl shadow-xl border border-blue-100">
    <!-- Header with decorative elements -->
    <div class="text-center mb-8 relative">
        <div class="absolute -top-6 -left-6 w-12 h-12 bg-blue-100 rounded-full opacity-30"></div>
        <div class="absolute -bottom-6 -right-6 w-16 h-16 bg-purple-100 rounded-full opacity-30"></div>
        
        <h2 class="text-2xl font-bold text-gray-800 relative z-10">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Upload Streaming Data</span>
        </h2>
        <p class="text-blue-600 mt-2">Share your music's performance with us</p>
    </div>
    
    <!-- Upload Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('Artists.Streams.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- File Upload with Creative Styling -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="usage_file" class="block text-sm font-medium text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        CSV File
                    </label>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Required</span>
                </div>
                
                <!-- Drag and Drop Area -->
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-blue-200 rounded-xl hover:border-blue-300 transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="usage_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="usage_file" name="usage_file" type="file" class="sr-only" accept=".csv,.txt" required>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">CSV or TXT up to 10MB</p>
                    </div>
                </div>
                
                <!-- File Requirements -->
                <div class="bg-blue-50 p-3 rounded-lg mt-3">
                    <h4 class="text-xs font-medium text-blue-800 mb-1">File Requirements:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Must include columns: <code class="bg-blue-100 px-1 rounded">isrc_code</code>, <code class="bg-blue-100 px-1 rounded">stream_count</code>, <code class="bg-blue-100 px-1 rounded">as_of_date</code>
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Date format: <code class="bg-blue-100 px-1 rounded">YYYY-MM-DD</code> (e.g., 2025-07-15)
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 group-hover:text-blue-100 transition-colors duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Process Streaming Data
                </button>
            </div>
        </form>
    </div>
    
    <!-- Template Download & Demo Data -->
    <div class="flex flex-col sm:flex-row justify-center gap-4 text-center">
        <a href="{{ route('Artists.Streams.download-template') }}" 
           class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Download CSV Template
        </a>
        
        @if(Auth::user()->artist && Auth::user()->artist->tracks()->count() > 0)
        <form action="{{ route('Artists.Streams.generate-demo') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                </svg>
                Generate Demo Data
            </button>
        </form>
        @endif
    </div>
</div>

<!-- File upload preview script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('usage_file');
        const dropZone = fileInput.closest('div');
        
        // Highlight drop zone when file is dragged over
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            });
        });
        
        // Handle file selection
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                dropZone.querySelector('span').textContent = fileInput.files[0].name;
            }
        });
    });
</script>
@endsection