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
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'google_map_place_id')) {
                $table->dropColumn('google_map_place_id');
            }
            $table->decimal('latitude', 10, 6)->nullable()->change();
            $table->decimal('longitude', 10, 6)->nullable()->change();
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('latitude', 255)->nullable()->change();
            $table->string('longitude', 255)->nullable()->change();
            $table->dropIndex(['latitude', 'longitude']);
        });
    }
};
