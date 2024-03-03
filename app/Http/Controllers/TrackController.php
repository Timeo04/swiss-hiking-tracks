<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Tracks/Index', [
            'tracks' => auth()->user()->tracks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tracks/Create', [
            // 'tracks' => auth()->user()->tracks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'starting_location' => ['string', 'max:255'],
            'destination_location' => ['string', 'max:255'],
            'gpx_file' => ['required', 'file'],
        ]);

        // $track = auth()->user()->tracks()->create($request->all());

        $track = Track::create([
            'title' => $request->input('title'),
            'starting_location' => $request->input('starting_location'),
            'destination_location' => $request->input('destination_location'),
            // 'gpx_file' => $request->file('gpx_file')->store('gpx_files'),
            'gpx_file' => "test.gpx",
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tracks.show', $track->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $track = Track::find($id);
        $track->delete();
    }
}
