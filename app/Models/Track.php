<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Relationen importieren
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// MediaLibrary importieren
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Track extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'starting_location',
        'destination_location',
        'geojson',
        'public',
        'share_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'geojson' => 'object',
    ];

    /**
     * Get the user that owns the track.
     * 
     * @var \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        // Ein Track gehört zu einem User
        return $this->belongsTo(User::class);
    }

    /**
     * Return the tracks which belong to a tag
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Media collections registrieren
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        // Media Collection für Bilder erstellen
        $this->addMediaCollection('images');
    }
}
