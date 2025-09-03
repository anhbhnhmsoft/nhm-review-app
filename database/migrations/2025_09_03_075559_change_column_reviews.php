<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'rating')) {
                $table->dropColumn('rating');
            }
            $table->tinyInteger('rating_location')->default(5)->comment('Đánh giá vị trí');
            $table->tinyInteger('rating_space')->default(5)->comment('Đánh giá không gian');
            $table->tinyInteger('rating_quality')->default(5)->comment('Đánh giá chất lượng');
            $table->tinyInteger('rating_serve')->default(5)->comment('Đánh giá phục vụ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('reviews', function (Blueprint $table) {
            $table->tinyInteger('rating')->default(5)->comment('Đánh giá tổng thể');
            $table->dropColumn([
                'rating_location',
                'rating_space',
                'rating_quality',
                'rating_serve',
            ]);
        });
    }
};
