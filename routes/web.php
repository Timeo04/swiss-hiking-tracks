<?php

/*
* Projektname: SwissHikingTracks
* Datum: 01.04.2024
* Autor*innen: Lea Geissmann, Yannis Bontempi, Cedric Bolleter
* Hauptquellen:
* - https://laravel.com/docs/
* - https://inertiajs.com/
* - https://svelte.dev/docs
* - https://tailwindcss.com/docs
* 
* Grobe Aufteilung des Codes:
* - Cedric: Authentifizierung, Profil/Settings, Geo-Daten-Verarbeitung, Datenbank, Bilder für Tracks, PopUps bei Karte
* - Lea: Grundlegende Operationen (CRUD, insb. bearbeiten, löschen) für Tracks, Tags hinzufügen, anzeigen und löschen
* - Yannis: Layout, Detail-Seite, Design, Kartenausschnitte, Wetter anzeigen
*/

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Startseite zurückgeben, mit Übergabeparametern
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard zurückgeben, wenn der User eingeloggt ist
Route::get('/dashboard', function () {
    // Tracks des eingeloggten Users laden
    $tracks = auth()->user()->tracks;
    // Alle Tags der Tracks abrufen
    $tracks->load('tags');
    if ($tracks != []) {
        foreach ($tracks as &$track) {
            // Erstes Bild des Tracks abrufen
            $image = $track->getFirstMedia('images');
            // Bild-URL dem Track hinzufügen
            $track['image_url'] = $image != null ? $image->getUrl() : null;
        }
    }
    // Dashboard-View mit Übergabewert tracks zurückgeben
    return Inertia::render('Dashboard', [
        'tracks' => $tracks,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Settings-Route-Gruppe, die nur eingeloggten Usern zur Verfügung steht
Route::prefix('/settings')->middleware('auth')->group(function () {
    // Profil-Bearbeiten-View zurückgeben
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Wandergeschwindigkeit setzen
    Route::post('/profile/hiking-speed', [ProfileController::class, 'setHikingSpeed'])->name('profile.setHikingSpeed');
    // Settings-View zurückgeben
    Route::get('/', [ProfileController::class, 'settings'])->name('settings')->middleware('auth');
});

// Tracks-Resource-Route, die nur eingeloggten Usern zur Verfügung steht
Route::resource('tracks', TrackController::class)->middleware('auth')->where(['track' => '[0-9]+']);

Route::prefix('/tracks')->middleware('auth')->group(function () {
    // GPX-Export-Route
    Route::get('{track}/gpx', [TrackController::class, 'gpx'])->name('tracks.gpx')->middleware('auth');
    // Weiterleitung zu SwissTopo-App mit Track
    Route::get('{track}/swisstopo', [TrackController::class, 'swisstopo'])->name('tracks.swisstopo')->middleware('auth');
    // Bild-Upload
    Route::post('{track}/image', [TrackController::class, 'storeImage'])->name('tracks.storeImage')->middleware('auth');
    // Bild-Reihenfolge ändern
    Route::post('{track}/image/order', [TrackController::class, 'updateImageOrder'])->name('tracks.updateImageOrder')->middleware('auth');
    // Bild löschen
    Route::delete('{track}/image/{image}', [TrackController::class, 'destroyImage'])->name('tracks.destroyImage')->middleware('auth');
    // Track teilen
    Route::post('{track}/share', [TrackController::class, 'share'])->name('tracks.share')->middleware('auth');
    // Track teilen aufheben
    Route::delete('{track}/share', [TrackController::class, 'unshare'])->name('tracks.unshare')->middleware('auth');
});

// Geteilten Track anzeigen
Route::get('tracks/{track_share_url}', [TrackController::class, 'showShare'])->name('tracks.showShare')->where(['track_share_url' => '[A-Za-z]+']);

// Tags-Resource-Route-Gruppe, die nur eingeloggten Usern zur Verfügung steht
Route::resource('tags', TagController::class)->middleware('auth');

// Verbindung Tags mit Tracks Route, die nur eingeloggten Usern zur Verfügung steht
Route::post('tracks/{track}/tag', [TrackController::class, 'tag'])->name('tracks.tag')->middleware('auth');

// Verbindung Tags mit Tracks aufheben Route, die nur eingeloggten Usern zur Verfügung steht
Route::post('tracks/{track}/untag', [TrackController::class, 'untag'])->name('tracks.untag')->middleware('auth');

require __DIR__ . '/auth.php';
