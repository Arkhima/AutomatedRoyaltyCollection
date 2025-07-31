<div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded-lg">
    <p>
        @if($selectedTrack)
            No streaming data found for "{{ $tracks->firstWhere('id', $selectedTrack)->title }}" 
            in {{ $currentMonth }}
        @else
            No streaming data found for {{ $currentMonth }}
        @endif
    </p>
</div>