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
        Schema::create('page_statics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Tiêu đề trang');                 
            $table->string('slug')->unique()->comment('Slug trang');        
            $table->longText('content')->comment('Nội dung trang'); 
            $table->text('excerpt')->comment('Miên tả ngắn');     
            $table->string('image_path')->nullable()->comment('Đường dẫn đến hình ảnh đại diện');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái trang 0: Kích hoạt, 1: Ẩn');
            $table->timestamp('published_at')->comment('Ngày xuất bản trang');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_statics', function (Blueprint $table) {
            $table->dropIfExists('page_statics');
        });
    }
};
