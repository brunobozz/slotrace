<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['drivers', 'cars', 'tracks', 'races'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('user_email')->nullable()->index()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['drivers', 'cars', 'tracks', 'races'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('user_email');
            });
        }
    }
};
