<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="background-color: #4CAF50; color: white; font-weight: bold;">
            <th>Quarter</th>
            <th>Track Title</th>
            <th>Artist</th>
            <th>ISRC Code</th>
            <th>Total Streams</th>
            <th>Total Royalty</th>
            <th>Rate per Stream</th>
            <th>As Of Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groupedRoyalties as $quarter => $items)
            @foreach ($items as $item)
                <tr>
                    <td>{{ $quarter }}</td>
                    <td>{{ $item['track_title'] }}</td>
                    <td>{{ $item['artist_name'] }}</td>
                    <td>{{ $item['isrc_code'] ?? 'N/A' }}</td>
                    <td style="text-align: right;">{{ number_format($item['total_streams']) }}</td>
                    <td style="text-align: right;">{{ number_format($item['total_royalty'], 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item['rate_per_stream'], 6) }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['as_of_date'])->toDateString() }}</td>
                </tr>
            @endforeach
        @endforeach

        {{-- Optional totals row --}}
        <tr style="font-weight: bold;">
            <td colspan="4" style="text-align: right;">Totals:</td>
            <td style="text-align: right;">{{ number_format($groupedRoyalties->flatten(1)->sum('total_streams')) }}</td>
            <td style="text-align: right;">{{ number_format($groupedRoyalties->flatten(1)->sum('total_royalty'), 2) }}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
