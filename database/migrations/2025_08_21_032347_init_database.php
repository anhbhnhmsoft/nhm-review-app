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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar_path')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('introduce')->nullable();
            $table->tinyInteger('role');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Tạo bảng provinces để lưu trữ thông tin về các tỉnh thành
        Schema::create('provinces', function (Blueprint $table) {;
            $table->id();
            $table->comment('Bảng provinces lưu trữ các tỉnh thành');
            $table->string('name')->comment('Tên');
            $table->string('code')->unique()->comment('Mã');
            $table->string('division_type')->nullable()->comment('Cấp hành chính');
            $table->timestamps();
        });

        // Tạo bảng districts để lưu trữ thông tin về các quận huyện
        Schema::create('districts', function (Blueprint $table) {;
            $table->id();
            $table->comment('Bảng districts lưu trữ các quận huyện');
            $table->string('name')->comment('Tên');
            $table->string('code')->unique()->comment('Mã');
            $table->string('division_type')->nullable()->comment('Cấp hành chính');
            $table->string('province_code');
            $table->foreign('province_code')->references('code')->on('provinces')->cascadeOnDelete();
            $table->timestamps();
        });

        // Tạo bảng districts để lưu trữ thông tin về các phường xã
        Schema::create('wards', function (Blueprint $table) {;
            $table->id();
            $table->comment('Bảng ward lưu trữ các phường xã');
            $table->string('name')->comment('Tên');
            $table->string('code')->unique()->comment('Mã');
            $table->string('division_type')->nullable()->comment('Cấp hành chính');

            // Khóa ngoại nối bằng code
            $table->string('district_code');
            $table->foreign('district_code')->references('code')->on('districts')->cascadeOnDelete();
            $table->timestamps();
        });

        // Tạo bảng categories để lưu trữ thông tin về các danh mục của cửa hàng
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng categories lưu trữ các danh mục của cửa hàng');
            $table->string('name')->comment('Tên danh mục');
            $table->boolean('show_header_home_page')->default(false)->comment('Cho phép hiển thị danh mục trên header trang chủ hay không');
            $table->boolean('show_index_home_page')->default(false)->comment('Cho phép hiển thị danh mục trên trang chủ hay không');
            $table->string('slug')->unique()->comment('Slug của danh mục, dùng để tạo URL thân thiện');
            $table->string('logo')->nullable()->comment('Logo của danh mục');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->smallInteger('status')->default(\App\Utils\Constants\CategoryStatus::ACTIVE->value)->comment('Trạng thái của danh mục: 1 - Hoạt động, 2 - Không hoạt động');
            $table->softDeletes();
            $table->timestamps();
        });

        // Tạo bảng store để lưu trữ thông tin về các cửa hàng
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng store lưu trữ thông tin về các cửa hàng');
            $table->string('name')->comment('Tên cửa hàng');
            $table->string('slug')->unique()->comment('Slug của cửa hàng');

            // Khóa ngoại liên kết danh mục
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade');

            // location
            $table->string('province_code')->comment('Mã tỉnh thành liên kết');
            $table->string('district_code')->comment('Mã quận huyện liên kết');
            $table->string('ward_code')->comment('Mã xã phường liên kết');
            $table->foreign('province_code')->references('code')->on('provinces')->cascadeOnDelete();
            $table->foreign('district_code')->references('code')->on('districts')->cascadeOnDelete();
            $table->foreign('ward_code')->references('code')->on('wards')->cascadeOnDelete();
            $table->string('address')->comment('Địa chỉ cụ thể');
            $table->decimal('latitude', 10, 6)->comment('Vĩ độ');
            $table->decimal('longitude', 10, 6)->comment('Kinh độ');

            // Thông tin liên hệ
            $table->string('logo_path')->comment('Logo của cửa hàng');
            $table->text('short_description')->comment('Mô tả ngắn về cửa hàng');
            $table->text('description')->comment('Mô tả chi tiết về cửa hàng');
            $table->string('phone')->nullable()->comment('Số điện thoại của cửa hàng');
            $table->string('email')->nullable()->comment('Email của cửa hàng');
            $table->string('website')->nullable()->comment('Website của cửa hàng');
            $table->string('facebook_page')->nullable()->comment('Trang Facebook của cửa hàng');
            $table->string('instagram_page')->nullable()->comment('Trang Instagram của cửa hàng');
            $table->string('tiktok_page')->nullable()->comment('Trang TikTok của cửa hàng');
            $table->string('youtube_page')->nullable()->comment('Kênh YouTube của cửa hàng');

            // Thông tin về thời gian hoạt động của cửa hàng
            $table->string('opening_time',10)->comment('Thời gian mở cửa');
            $table->string('closing_time',10)->comment('Thời gian đóng cửa');

            $table->tinyInteger('status')->comment('Trạng thái, Lưu trong enum StoreStatus');
            $table->string('view')->default('0')->comment('Số lượt xem của cửa hàng, lưu trữ dưới dạng chuỗi để tránh lỗi tràn số nguyên');
            $table->boolean('featured')->default(false)->comment('Cửa hàng nổi bật, mặc định là false');
            $table->integer('sorting_order')->nullable()->comment('Thứ tự sắp xếp của cửa hàng, nếu cửa hàng nổi bật thì sẽ có thứ tự sắp xếp cao hơn các cửa hàng khác');

            $table->softDeletes();
            $table->timestamps();
        });

        // Tạo bảng store_files để lưu trữ các tệp đính kèm liên quan đến cửa hàng (ảnh, video của cửa hàng)
        Schema::create('store_files', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng store_files lưu trữ các tệp tin liên quan đến cửa hàng');
            $table->foreignId('store_id')
                ->constrained('stores')
                ->onDelete('cascade');
            $table->string('file_path')->comment('Đường dẫn đến tệp đính kèm');
            $table->string('file_name')->comment('Tên tệp đính kèm');
            $table->string('file_extension')->comment('Phần mở rộng của tệp đính kèm, ví dụ: pdf, docx, jpg, v.v.');
            $table->string('file_size')->comment('Kích thước tệp đính kèm, lưu trữ dưới dạng chuỗi (ví dụ: "2MB", "500KB")');
            $table->string('file_type')->comment('Loại tệp đính kèm, ví dụ: pdf, docx, jpg, v.v.');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng bookings để lưu trữ các lịch đặt của khách hàng
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                ->nullable()
                ->constrained('stores')
                ->onDelete('cascade');
            $table->string('customer_name')->comment('Tên khách hàng đặt lịch');
            $table->string('customer_phone')->comment('Số điện thoại khách hàng đặt lịch');
            $table->string('customer_email')->nullable()->comment('Email khách hàng đặt lịch');
            $table->text('note')->nullable()->comment('Ghi chú thêm từ khách hàng');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng reviews để lưu trữ các đánh giá của người dùng về cửa hàng
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng store_reviews lưu trữ các đánh giá của người dùng về cửa hàng');
            $table->foreignId('store_id')
                ->constrained('stores')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->tinyInteger('rating_location')->default(5)->comment('Đánh giá vị trí');
            $table->tinyInteger('rating_space')->default(5)->comment('Đánh giá không gian');
            $table->tinyInteger('rating_quality')->default(5)->comment('Đánh giá chất lượng');
            $table->tinyInteger('rating_serve')->default(5)->comment('Đánh giá phục vụ');
            $table->text('review')->nullable()->comment('Nội dung đánh giá của người dùng');
            $table->boolean('is_anonymous')->default(false)->comment('Đánh dấu xem đánh giá có ẩn danh hay không');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng review_images để lưu trữ các hình ảnh liên quan đến đánh giá của người dùng
        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng review_images lưu trữ các hình ảnh liên quan đến đánh giá của người dùng');
            $table->foreignId('review_id')
                ->constrained('reviews')
                ->onDelete('cascade');
            $table->string('image_path')->comment('Đường dẫn đến hình ảnh đánh giá');
            $table->string('image_name')->comment('Tên hình ảnh đánh giá');
            $table->string('image_extension')->comment('Phần mở rộng của hình ảnh đánh giá, ví dụ: jpg, png, v.v.');
            $table->string('image_size')->comment('Kích thước hình ảnh đánh giá, lưu trữ dưới dạng chuỗi (ví dụ: "2MB", "500KB")');
            $table->string('image_type')->comment('Loại hình ảnh đánh giá, ví dụ: jpg, png, v.v.');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng articles để lưu trữ các bài viết
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng articles lưu trữ các bài viết');
            $table->string('slug')->unique()->comment('Slug của bài viết, dùng để tạo URL thân thiện');
            $table->string('title')->comment('Tiêu đề bài viết');
            $table->text('content')->comment('Nội dung bài viết');
            $table->string('author')->comment('Tác giả của bài viết');
            $table->string('image_path')->nullable()->comment('Đường dẫn đến hình ảnh đại diện của bài viết');
            $table->string('view')->default('0')->comment('Số lượt xem của bài viết, lưu trữ dưới dạng chuỗi để tránh lỗi tràn số nguyên');
            $table->bigInteger('sort')->default(0)->comment('Thứ tự sắp xếp của bài viết, số nhỏ sẽ được ưu tiên hiển thị trước');
            $table->tinyInteger('type')->comment('Loại bài viết');
            $table->string('seo_title')->nullable()->comment('Tiêu đề SEO của bài viết, dùng để tối ưu hóa công cụ tìm kiếm');
            $table->string('seo_description')->nullable()->comment('Mô tả SEO của bài viết, dùng để tối ưu hóa công cụ tìm kiếm');
            $table->string('seo_keywords')->nullable()->comment('Từ khóa SEO của bài viết, dùng để tối ưu hóa công cụ tìm kiếm');
            $table->tinyInteger('status')->comment('Trạng thái của bài viết, lưu trữ trong enum ArticleStatus');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng banners để lưu trữ các banner quảng cáo
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng banners lưu trữ banner quảng cáo');
            $table->boolean('banner_index')->default(false)->comment('Banner hiển thị ở trang chủ');
            $table->string('link')->nullable()->comment('Liên kết của banner');
            $table->string('image_path')->nullable()->comment('Đường dẫn đến hình ảnh đại diện');
            $table->string('alt_banner')->nullable()->comment('alt banner seo');
            $table->bigInteger('sort')->default(0)->comment('Thứ tự sắp xếp, số nhỏ sẽ được ưu tiên hiển thị trước');
            $table->boolean('show')->comment('Trạng thái');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tạo bảng configs để lưu trữ các cấu hình của hệ thống
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->comment('Bảng configs lưu trữ các cấu hình của hệ thống');
            $table->string('config_key')->unique();
            $table->smallInteger('config_type')->nullable()->comment('Loại cấu hình 1: Type image, 2: Type string');
            $table->text('config_value');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('utilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên tiện ích');
            $table->text('description')->nullable()->comment('Mô tả tiện ích, nếu có');
            $table->text('icon_svg')->nullable()->comment('icon tiện ích, nếu có');
            $table->timestamps();
        });

        Schema::create('store_utility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                ->constrained('stores')
                ->onDelete('cascade');
            $table->foreignId('utility_id')
                ->constrained('utilities')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_utility');
        Schema::dropIfExists('utilities');
        Schema::dropIfExists('configs');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('store_files');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('wards');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('banners');
    }
};
