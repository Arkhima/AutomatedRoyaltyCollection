<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Artist;
use App\Models\Track;

class SearchController extends Controller
{
    /**
     * Display search results
     */
    public function index(Request $request)
    {
        $query = $request->get('q');

        // Return empty results if query is too short
        if (!$query || strlen($query) < 2) {
            return view('Admin.search.results', [
                'users' => collect([]),
                'artists' => collect([]),
                'tracks' => collect([]),
                'query' => $query,
            ]);
        }

        // Users search
        $users = User::where('id', 'LIKE', "%{$query}%")
                     ->orWhere('name', 'LIKE', "%{$query}%")
                     ->orWhere('email', 'LIKE', "%{$query}%")
                     ->orWhere('username', 'LIKE', "%{$query}%")
                     ->limit(10)
                     ->get();

        // Artists search
        $artists = Artist::where('id', 'LIKE', "%{$query}%")
                         ->orWhere('name', 'LIKE', "%{$query}%")
                         ->orWhere('stage_name', 'LIKE', "%{$query}%")
                         ->orWhere('bio', 'LIKE', "%{$query}%")
                         ->with('user')
                         ->limit(10)
                         ->get();

        // Tracks search
        $tracks = Track::where('id', 'LIKE', "%{$query}%")
                       ->orWhere('title', 'LIKE', "%{$query}%")
                       ->orWhere('description', 'LIKE', "%{$query}%")
                       ->orWhere('genre', 'LIKE', "%{$query}%")
                       ->orWhereHas('artist', function($q) use ($query) {
                           $q->where('name', 'LIKE', "%{$query}%");
                       })
                       ->with('artist')
                       ->limit(10)
                       ->get();

        return view('Admin.search.results', compact('users', 'artists', 'tracks', 'query'));
    }

    /**
     * Quick search for AJAX/live search
     */
    public function quick(Request $request)
    {
        $query = $request->get('q');

        $results = [
            'users' => User::where('name', 'LIKE', "%{$query}%")
                           ->orWhere('email', 'LIKE', "%{$query}%")
                           ->limit(5)
                           ->get(['id', 'name', 'email']),
            'artists' => Artist::where('name', 'LIKE', "%{$query}%")
                               ->orWhere('stage_name', 'LIKE', "%{$query}%")
                               ->limit(5)
                               ->get(['id', 'name', 'stage_name']),
            'tracks' => Track::where('title', 'LIKE', "%{$query}%")
                             ->limit(5)
                             ->get(['id', 'title', 'artist_id']),
        ];

        return response()->json($results);
    }
}