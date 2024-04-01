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
        // Users Tabelle anpassen
        Schema::table('users', function (Blueprint $table) {
            $table->after('email', function ($table) {
                // Spalte hiking_speed hinzufügen
                $table->float('hiking_speed')->default(4.2);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users Tabelle anpassen
        Schema::table('users', function (Blueprint $table) {
            // Spalte hiking_speed entfernen
            $table->dropColumn('hiking_speed');
        });
    }
};
