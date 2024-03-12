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
        // Tracks-Tabelle erstellen
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('starting_location')->nullable()->default(null);
            $table->string('destination_location')->nullable()->default(null);
            $table->json('geojson');
            $table->boolean('public')->default(false);
            
            $table->timestamps();

            // Foreign key constraint hinzufügen
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the tracks table
        Schema::dropIfExists('tracks');
    }
};
