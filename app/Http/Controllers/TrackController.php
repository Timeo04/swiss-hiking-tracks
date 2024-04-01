<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Track-Model importieren
use App\Models\Track;
use App\Models\Tag;
use DOMDocument;
// Inertia importieren
use Inertia\Inertia;
// GeoJsonRule und LineString für Validation importieren
use YucaDoo\LaravelGeoJsonRule\GeoJsonRule;
use GeoJson\Geometry\LineString;
// Media-Model für Bilder bei Tracks importieren
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $tracks = auth()->user()->tracks;
        $tracks->load('tags');
        // Inertia-Response mit Übergabewert tracks (alle Tracks des Users) zurückgeben
        return Inertia::render('Tracks/Index', [
            'tracks' => $tracks
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
        ]);

        // Create a new track with the validated data
        $track = Track::create([
            'title' => $request->input('title'),
            'starting_location' => $request->input('starting_location'),
            'destination_location' => $request->input('destination_location'),
            'geojson' => json_decode($request->input('geojson')),
            'user_id' => auth()->id(), // Get User ID from the authenticated user
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
        //Alle Tags des Tracks abrufen 
        $track->load('tags');

        // Alle Bilder des Tracks abrufen und in ein Array mit id und URL umwandeln
        $images = $track->getMedia('images')->map(fn ($media) => ['id' => $media->id, 'url' => $media->getUrl()]);
        // Inertia-Response mit Übergabewert $track und $images zurückgeben

        return Inertia::render('Tracks/Show', [
            'track' => $track,
            'images' => $images,
        ]);
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function showShare(String $track_share_url): \Inertia\Response
    {
        // Get Track by share_url
        $track = Track::where('share_url', $track_share_url)->firstOrFail();

        // Inertia-Response mit Übergabewert $track zurückgeben
        return Inertia::render('Tracks/Show', [
            'track' => $track,
            'images' => $track->getMedia('images')->map(fn ($media) => ['id' => $media->id, 'url' => $media->getUrl()]),
            'shared' => true
        ]);
    }

    /**
     * Bild zu einem Track hinzufügen.
     *
     * @param Request $request
     * @param Track $track
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeImage(Request $request, Track $track): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'image' => ['required', 'image']
        ]);

        // Bild hochladen und zur MediaCollection 'images' hinzufügen
        $track->addMediaFromRequest('image')
            ->toMediaCollection('images');  // 'images' ist der Name der MediaCollection

        // Redirect to the show view of the track
        return to_route('tracks.show', $track);
    }

    /**
     * Generate and return GPX file of the specified Track.
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\Response
     */
    public function gpx(Track $track): \Illuminate\Http\Response
    {
        // LineString aus Datenbank abrufen
        $lineString = $track->geojson;
        // Koordinaten aus LineString extrahieren
        $coordinates = $lineString->coordinates;

        $current_time = date('Y-m-d\TH:i:s\Z'); // ISO 8601 format
        $gpx = '<?xml version="1.0"?>
        <gpx version="1.1" creator="Generated by SwissHikingTracks." xmlns="http://www.topografix.com/GPX/1/1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:swisstopo="https://prod-static.swisstopo-app.ch/xmlschemas/SwisstopoExtensions" xmlns:schemaLocation="http://www.topografix.com/GPX/1/1">
            <metadata>
                <time>' . $current_time . '</time>
                <extensions>
                    <swisstopo:tour_type>0</swisstopo:tour_type>
                </extensions>
                <name>' . $track->title . '</name>
                <desc />
            </metadata>
            <trk>
                <name>' . $track->title . '</name>
                <trkseg>
        ';

        foreach ($coordinates as $coordinate) {
            $gpx .= '<trkpt lat="' . $coordinate[1] . '" lon="' . $coordinate[0] . '">
            ';
            if (sizeof($coordinate) > 2) {
                $gpx .= '<ele>' . $coordinate[2] . '</ele>
                ';
            }
            $gpx .= '</trkpt>
            ';
        }

        $gpx .= '</trkseg>
            </trk>
        </gpx>';

        // DOMDocument erstellen und gpx-Datei laden
        $doc = new DOMDocument('1.0');
        $doc->loadXML($gpx);
        $doc->formatOutput = true;
        $doc->preserveWhiteSpace = false;

        // Response mit gpx-Datei zurückgeben
        return response($doc->saveXML(), 200, [
            'Content-Type' => 'application/gpx+xml',
            'Content-Disposition' => 'attachment; filename="' . $track->title . '.gpx"',
        ]);
    }

    /**
     * Generate and redirect to Swisstopo with the specified Track.
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\RedirectResponse
     */
    public function swisstopo(Track $track)
    {
        // GPX-URL generieren
        $gpxUrl = route('tracks.gpx', $track);

        // Base64-URL generieren
        // https://www.php.net/manual/en/function.base64-encode.php
        $gpxUrlBase64 = rtrim(strtr(base64_encode($gpxUrl), '+/', '-_'), '=');

        // Swisstopo-URL generieren
        $swisstopoUrl = "https://swisstopo.app/u/";
        $swisstopoUrl .= $gpxUrlBase64;

        // Redirect to Swisstopo
        return redirect($swisstopoUrl);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Track $track)
    {
        // Inertia-Response mit Übergabewert $track zurückgeben
        return Inertia::render('Tracks/Edit', [
            'track' => $track
        ]);
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
            'starting_location' => ['nullable', 'string', 'max:255'],
            'destination_location' => ['nullable', 'string', 'max:255'],
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
     * Update the order of the images of the specified Track in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Track  $track
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImageOrder(Request $request, Track $track): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'order' => ['required', 'array']
        ]);

        // Set the new order of the images
        Media::setNewOrder($request->input('order'));

        // Redirect to the show view of the track
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

    /**
     * Tag zu einem Track hinzufügen
     *
     * @param Request $request
     * @param Track $track
     * @return void
     */
    public function tag(Request $request, Track $track)
    {
        // Validate the request data
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $name = $request->input('name');

        // Suche nach dem Tag in der Datenbank oder erstelle ihn, falls er nicht existiert
        $tag = Tag::firstOrCreate(['name' => $name]);

        // ID des Tags, unabhängig davon, ob er neu erstellt wurde oder nicht
        $tagId = $tag->id;

        $track->tags()->attach($tagId);

        // Weiterleitung zur Track-Seite
        return to_route('tracks.show', $track);
    }
  
    public function untag(Request $request, Track $track)
    {
        // Validate the request data
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $tagId = $request->input('id');

        $track->tags()->detach($tagId);

        // Weiterleitung zur Track-Seite
        return to_route('tracks.show', $track);
    }


    /**
     * Bild von einem Track löschen
     *
     * @param Request $request
     * @param Track $track
     * @return void
     */
    public function destroyImage(Request $request, Track $track, int $image): \Illuminate\Http\RedirectResponse
    {
        // Bilder abrufen
        $images = $track->getMedia('images');

        // Bild finden und löschen
        $image = $images->firstWhere('id', $image);
        $image->delete();

        // Redirect to the show view of the track
        return to_route('tracks.show', $track);
    }

    /**
     * Track teilen
     *
     * @param Request $request
     * @param Track $track
     * @return \Illuminate\Http\RedirectResponse
     */
    public function share(Request $request, Track $track): \Illuminate\Http\RedirectResponse
    {
        // Zufälliger String für Share generieren
        $random_string = TrackController::generateRandomString(8);
        // Überprüfen, ob der String bereits existiert oder einen neuen generieren
        while (Track::where('share_url', $random_string)->exists()) {
            $random_string = TrackController::generateRandomString(8);
        }

        // String als share_url in der Datenbank speichern
        $track->share_url = $random_string;
        $track->save();

        // Redirect to the show view of the track
        return to_route('tracks.show', $track);
    }

    /**
     * Track nicht mehr teilen
     *
     * @param Request $request
     * @param Track $track
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unshare(Request $request, Track $track): \Illuminate\Http\RedirectResponse
    {
        // share_url in der Datenbank löschen
        $track->share_url = null;
        $track->save();

        // Redirect to the show view of the track
        return to_route('tracks.show', $track);
    }

    /**
     * Zufälligen String generieren
     *
     * @param integer $length
     * @return string
     */
    public static function generateRandomString($length = 8)
    {
        // Quelle: https://stackoverflow.com/a/4356295
        // Alle möglichen Zeichen als String
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        // Leeren String initialisieren
        $randomString = '';
        // Für die Länge des Strings zufällige Zeichen auswählen
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        // Zufälligen String zurückgeben
        return $randomString;
    }
}
