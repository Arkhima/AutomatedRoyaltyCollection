@extends('layouts.Artists.Dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section with Decorative Elements -->
    <div class="text-center mb-8 relative">
        <div class="absolute -top-5 left-0 w-16 h-16 bg-green-100 rounded-full opacity-20"></div>
        <div class="absolute bottom-0 right-0 w-20 h-20 bg-teal-100 rounded-full opacity-20"></div>
        
        <h1 class="text-3xl font-bold text-gray-800 relative z-10">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-teal-600">Update Artist Profile</span>
        </h1>
        <p class="text-gray-600 mt-2">Edit the artist details below</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <form action="{{ route('Artists.ArtistSection.update', $artist->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf
            @method('PUT')

            <!-- Two Column Layout for Larger Screens -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Legal Name
                        </label>
                        <input type="text" name="name" value="{{ old('name', $artist->name) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                               placeholder="John Doe">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                            </svg>
                            Stage Name
                        </label>
                        <input type="text" name="stage_name" value="{{ old('stage_name', $artist->stage_name) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150"
                               placeholder="Artist Name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email Address
                        </label>
                        <input type="email" name="email" value="{{ old('email', $artist->email) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                               placeholder="artist@example.com">
                    </div>
                </div>

                <!-- Financial & Legal Information -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Phone Number
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $artist->phone) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150"
                               placeholder="+254 700 000000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Bank Details
                        </label>
                        <input type="text" name="bank_details" value="{{ old('bank_details', $artist->bank_details) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                               placeholder="Bank Name, Account Number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            National ID
                        </label>
                        <input type="text" name="national_id" value="{{ old('national_id', $artist->national_id) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150"
                               placeholder="ID Number">
                    </div>
                </div>
            </div>


            <!-- Submit and Cancel Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <button type="submit" class="group relative px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-medium rounded-lg shadow-md hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-200 group-hover:text-green-100 transition duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Update Artist Profile
                </button>
                <a href="{{ route('Artists.ArtistSection.index') }}" class="px-8 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- File upload preview script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="photo"]');
        const dropZone = fileInput.closest('label');
        
        // Highlight drop zone when file is dragged over
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('border-green-500', 'bg-green-50');
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-green-500', 'bg-green-50');
            });
        });
        
        // Handle file selection
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                const fileName = fileInput.files[0].name;
                const infoText = dropZone.querySelector('p');
                infoText.textContent = fileName;
                infoText.classList.add('text-green-600', 'font-medium');
            }
        });
    });
</script>
@endsection