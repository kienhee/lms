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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id'); // Giảng viên
            $table->unsignedBigInteger('category_id')->nullable(); // Danh mục khóa học
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable(); // Mô tả ngắn
            $table->text('content')->nullable(); // Nội dung chi tiết
            $table->string('thumbnail')->nullable(); // Ảnh đại diện
            $table->decimal('price', 10, 2)->default(0); // Giá gốc
            $table->decimal('sale_price', 10, 2)->nullable(); // Giá khuyến mãi
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'all'])->default('all'); // Cấp độ
            $table->string('language')->default('vi'); // Ngôn ngữ
            $table->integer('duration')->default(0); // Tổng thời lượng (phút)
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false); // Khóa học nổi bật
            $table->boolean('is_free')->default(false); // Khóa học miễn phí
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['title', 'slug']);
            $table->index(['status', 'is_featured']);
            $table->index('category_id');
            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
