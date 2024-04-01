<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tags Pivot Tabelle erstellen
        Schema::create('tag_track', function (Blueprint $table) {
            $table->unsignedBigInteger('track_id');
            $table->unsignedBigInteger('tag_id');

            // Foreign Keys setzen
            $table->foreign('track_id')->references('id')->on('tracks');
            $table->foreign('tag_id')->references('id')->on('tags');

            // Zusammengesetzten Primärschlüssel setzen
            $table->primary(['tag_id', 'track_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tags Pivot Tabelle löschen
        Schema::dropIfExists('tag_track');
    }
};
