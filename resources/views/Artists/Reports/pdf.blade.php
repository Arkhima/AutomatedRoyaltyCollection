<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Royalty Report PDF</title>
    <style>
        @page {
            margin: 50px 40px 70px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
        }

        header {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            height: 50px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        header .date-range {
            font-size: 12px;
            color: #666;
        }

        footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
            line-height: 40px;
        }

        footer .page:after {
            content: counter(page);
        }

        .quarter-section {
            margin-top: 70px;
            margin-bottom: 30px;
        }

        h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 6px;
            margin-bottom: 15px;
            font-size: 16px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table, th, td {
            border: 1px solid #aaa;
        }

        th, td {
            padding: 8px 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .right-align {
            text-align: right;
        }
    </style>
</head>
<body>

<header>
    <h1>Royalty Report</h1>
    <div class="date-range">
        Report Period: {{ $startDate ?? 'N/A' }} to {{ $endDate ?? 'N/A' }}
    </div>
</header>

<footer>
    Page <span class="page"></span>
</footer>

<main>
    @foreach($streams as $quarter => $items)
        <div class="quarter-section">
            <h2>{{ $quarter }}</h2>

            <table>
                <thead>
                    <tr>
                        <th>Track Title</th>
                        <th>ISRC Code</th>
                        <th>Artist Name</th>
                        <th>Streams</th>
                        <th>Rate per Stream</th>
                        <th>Royalty Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $quarterStreamsTotal = 0;
                        $quarterRoyaltyTotal = 0;
                    @endphp

                    @foreach($items as $item)
                        @php
                            $streamsCount = $item->stream_count;
                            $rate = $item->track->rate_per_stream;
                            $royaltyAmount = $streamsCount * $rate;
                            $quarterStreamsTotal += $streamsCount;
                            $quarterRoyaltyTotal += $royaltyAmount;
                        @endphp
                        <tr>
                            <td>{{ $item->track->title }}</td>
                            <td>{{ $item->track->isrc_code }}</td>
                            <td>{{ $item->track->artist->name }}</td>
                            <td class="right-align">{{ number_format($streamsCount) }}</td>
                            <td class="right-align">${{ number_format($rate, 6) }}</td>
                            <td class="right-align">${{ number_format($royaltyAmount, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="total-row">
                        <td colspan="3">Total for {{ $quarter }}</td>
                        <td class="right-align">{{ number_format($quarterStreamsTotal) }}</td>
                        <td></td>
                        <td class="right-align">${{ number_format($quarterRoyaltyTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
</main>

</body>
</html>
