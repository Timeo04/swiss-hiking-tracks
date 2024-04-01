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
        // Tracks Tabelle anpassen
        Schema::table('tracks', function (Blueprint $table) {
            $table->after('public', function ($table) {
                // Spalte share_url hinzufügen
                $table->string('share_url')->nullable()->default(null)->unique();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tracks Tabelle anpassen
        Schema::table('tracks', function (Blueprint $table) {
            // Spalte share_url entfernen
            $table->dropColumn('share_url');
        });
    }
};
