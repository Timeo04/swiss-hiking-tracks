<?php

/*
* Projektname: SwissHikingTracks
* Datum: 13.03.2024
* Autor*innen: Lea Geissmann, Yannis Bontempi, Cedric Bolleter
* Hauptquellen:
* - https://laravel.com/docs/
* - https://inertiajs.com/
* - https://svelte.dev/docs
* - https://tailwindcss.com/docs
* 
* Grobe Aufteilung des Codes:
* - Cedric: Authentifizierung, Profil/Settings, Geo-Daten-Verarbeitung, Datenbank
* - Lea: Grundlegende Operationen (CRUD, insb. bearbeiten, löschen) für Tracks
* - Yannis: Layout, Detail-Seite, Design, Kartenausschnitte
*/

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TrackController;

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
    return Inertia::render('Dashboard', [
        'tracks' => auth()->user()->tracks,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Settings-Route-Gruppe, die nur eingeloggten Usern zur Verfügung steht
Route::prefix('/settings')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/hiking-speed', [ProfileController::class, 'setHikingSpeed'])->name('profile.setHikingSpeed');
    Route::get('/', [ProfileController::class, 'settings'])->name('settings')->middleware('auth');
});

// Tracks-Resource-Route-Gruppe, die nur eingeloggten Usern zur Verfügung steht
Route::resource('tracks', TrackController::class)->middleware('auth')->where(['track' => '[0-9]+']);
Route::get('tracks/{track}/gpx', [TrackController::class, 'gpx'])->name('tracks.gpx')->middleware('auth');
Route::post('tracks/{track}/image', [TrackController::class, 'storeImage'])->name('tracks.storeImage')->middleware('auth');
Route::post('tracks/{track}/image/order', [TrackController::class, 'updateImageOrder'])->name('tracks.updateImageOrder')->middleware('auth');
Route::delete('tracks/{track}/image/{image}', [TrackController::class, 'destroyImage'])->name('tracks.destroyImage')->middleware('auth');
Route::post('tracks/{track}/share', [TrackController::class, 'share'])->name('tracks.share')->middleware('auth');
Route::delete('tracks/{track}/share', [TrackController::class, 'unshare'])->name('tracks.unshare')->middleware('auth');

Route::get('tracks/{track_share_url}', [TrackController::class, 'showShare'])->name('tracks.showShare')->where(['track_share_url' => '[A-Za-z]+']);

require __DIR__ . '/auth.php';
