@extends('Layouts.Admin.admin')


@section('title','Tracks')

@section('content')
    <div class="bg-white p-4 rounded shadow">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="text-left">Title</th>
                    <th class="text-left">Artist</th>
                    <th class="text-left">Rate</th>
                    <th class="text-left">Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tracks as $track)
                    <tr class="border-t">
                        <td>{{ $track->title }}</td>
                        <td>{{ $track->artist->name ?? '—' }}</td>
                        <td>{{ $track->rate_per_stream }}</td>
                        <td>{{ $track->created_at->toDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $tracks->links() }}</div>
    </div>
@endsection
