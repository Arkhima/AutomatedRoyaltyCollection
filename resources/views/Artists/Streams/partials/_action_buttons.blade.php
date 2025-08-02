<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Streaming Data</h1>
    <div class="flex gap-3">
        <a href="{{ route('Artists.Streams.upload.form') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            Import CSV
        </a>
        <form action="{{ route('Artists.Streams.generate-demo') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Generate Streams Data
            </button>
        </form>
    </div>
</div>