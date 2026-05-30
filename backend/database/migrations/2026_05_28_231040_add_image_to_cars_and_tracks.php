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
        Schema::table('cars', function (Blueprint $table) {
            $table->string('image')->nullable()->after('scale');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->string('image')->nullable()->after('length_meters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
