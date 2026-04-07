<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;

class TracksController extends Controller
{
    /**
     * Display a paginated list of tracks
     */
    public function index()
    {
        $tracks = Track::with('artist')
                       ->orderBy('created_at', 'desc')
                       ->paginate(25);

        return view('Admin.tracks.index', compact('tracks'));
    }

    /**
     * Show form to create new track
     */
    public function create()
    {
        return view('Admin.tracks.create');
    }

    /**
     * Store new track in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist_id' => 'required|exists:artists,id',
            'isrc_code' => 'nullable|string|max:50|unique:tracks',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:100',
            'duration' => 'nullable|integer',
            'rate_per_stream' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,pending',
        ]);

        Track::create($validated);

        return redirect()->route('admin.tracks.index')
                         ->with('success', 'Track created successfully.');
    }

    /**
     * Show details for a single track
     */
    public function show(Track $track)
    {
        return view('Admin.tracks.show', compact('track'));
    }

    /**
     * Delete track from database
     */
    public function destroy(Track $track)
    {
        $track->delete();
        
        return redirect()->route('admin.tracks.index')
                         ->with('success', 'Track deleted successfully.');
    }

    /**
     * Export tracks to CSV
     */
    public function exportCsv()
    {
        $tracks = Track::with('artist')->orderBy('created_at', 'desc')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tracks_' . now()->format('Y-m-d_H-i-s') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($tracks) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Track ID', 
                'Title', 
                'Artist Name', 
                'Album',
                'ISRC Code', 
                'Genre',
                'Rate Per Stream', 
                'Total Streams', 
                'Status', 
                'Created Date'
            ]);

            foreach ($tracks as $track) {
                fputcsv($file, [
                    $track->id,
                    $track->title,
                    $track->artist->name ?? 'N/A',
                    $track->album ?? 'N/A',
                    $track->isrc_code ?? 'N/A',
                    $track->genre ?? 'N/A',
                    $track->rate_per_stream ?? 0,
                    $track->total_streams ?? 0,
                    $track->status ?? 'active',
                    $track->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tracks to PDF
     */
    public function exportPdf()
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf package. Run: composer require barryvdh/laravel-dompdf');
        }

        $tracks = Track::with('artist')->orderBy('created_at', 'desc')->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Admin.tracks.export-pdf', compact('tracks'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('tracks_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}