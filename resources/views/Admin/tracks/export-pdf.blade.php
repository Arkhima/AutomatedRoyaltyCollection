<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tracks Report - {{ now()->format('Y-m-d') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #4a5568; color: white; padding: 8px; text-align: left; }
        td { padding: 6px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #2d3748; }
        .header p { color: #718096; margin: 5px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #a0aec0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Automated Royalty Collection System</h1>
        <h2>Tracks Report</h2>
        <p>Generated on: {{ now()->format('F d, Y H:i:s') }}</p>
        <p>Total Tracks: {{ $tracks->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Artist</th>
                <th>ISRC Code</th>
                <th class="text-right">Rate/Stream</th>
                <th class="text-right">Total Streams</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tracks as $index => $track)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $track->title }}</td>
                <td>{{ $track->artist->name ?? 'N/A' }}</td>
                <td>{{ $track->isrc_code ?? '—' }}</td>
                <td class="text-right">${{ number_format($track->rate_per_stream ?? 0, 4) }}</td>
                <td class="text-right">{{ number_format($track->total_streams ?? 0) }}</td>
                <td class="text-center">{{ ucfirst($track->status ?? 'active') }}</td>
                <td>{{ $track->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} ARC System - Confidential Report</p>
    </div>
</body>
</html>