<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Royalty Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        .quarter-title {
            margin-top: 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Royalty Report</h2>
        <p>Generated on {{ \Carbon\Carbon::now()->toFormattedDateString() }}</p>
    </div>

    @foreach($streams as $quarter => $records)
        <div class="quarter-title">{{ $quarter }}</div>
        <table>
            <thead>
                <tr>
                    <th>Track Title</th>
                    <th>ISRC Code</th>
                    <th>Artist</th>
                    <th>Streams</th>
                    <th>Rate Per Stream (Ksh)</th>
                    <th>Total Royalty (Ksh)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $stream)
                    <tr>
                        <td>{{ $stream->track->title ?? 'N/A' }}</td>
                        <td>{{ $stream->track->isrc_code ?? 'N/A' }}</td>
                        <td>{{ $stream->track->artist->name ?? 'N/A' }}</td>
                        <td>{{ $stream->stream_count }}</td>
                        <td>{{ number_format($stream->track->rate_per_stream ?? 0, 2) }}</td>
                        <td>{{ number_format(($stream->stream_count * $stream->track->rate_per_stream), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
