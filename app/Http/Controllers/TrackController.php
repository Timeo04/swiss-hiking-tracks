<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Track-Model importieren
use App\Models\Track;
// Inertia importieren
use Inertia\Inertia;
// GeoJsonRule für Validation importieren
use YucaDoo\LaravelGeoJsonRule\GeoJsonRule;
use GeoJson\Geometry\LineString;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        // Inertia-Response mit Übergabewert tracks (alle Tracks des Users) zurückgeben
        return Inertia::render('Tracks/Index', [
            'tracks' => auth()->user()->tracks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Tracks/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'title' => ['required', 'max:255'],
            'starting_location' => ['nullable', 'string', 'max:255'],
            'destination_location' => ['nullable', 'string', 'max:255'],
            'geojson' => ['required', new GeoJsonRule(LineString::class)],
            // 'gpx_file' => ['required', 'file'],
        ]);

        // Create a new track with the validated data
        $track = Track::create([
            'title' => $request->input('title'),
            'starting_location' => $request->input('starting_location'),
            'destination_location' => $request->input('destination_location'),
            // 'gpx_file' => "test.gpx",
            'geojson' => $request->input('geojson'),
            'user_id' => auth()->id(),
        ]);

        // Redirect to the show view of the newly created track
        return to_route('tracks.show', $track);
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track): \Inertia\Response
    {
        // Inertia-Response mit Übergabewert $track zurückgeben
        return Inertia::render('Tracks/Show', [
            'track' => $track
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified Track in storage.
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'title' => ['required', 'max:255'],
            'starting_location' => ['string', 'max:255'],
            'destination_location' => ['string', 'max:255'],
        ]);

        // Update the track with the validated data
        $track = Track::findOrFail($id);
        $track->title = $request->input('title');
        $track->starting_location = $request->input('starting_location');
        $track->destination_location = $request->input('destination_location');

        $track->save();

        // Redirect to the show view of the updated track
        return to_route('tracks.show', $track);
    }

    /**
     * Remove the specified Track from storage.
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        // Find the track and delete it
        $track = Track::findOrFail($id);
        $track->delete();

        // Redirect to the index view of the tracks
        return to_route('tracks.index');
    }
}
