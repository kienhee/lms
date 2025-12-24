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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('type', ['video', 'text', 'quiz', 'assignment'])->default('video');
            $table->string('video_url')->nullable(); // URL video
            $table->string('video_duration')->nullable(); // Thời lượng video (ví dụ: "10:30")
            $table->text('content')->nullable(); // Nội dung bài học (cho type text)
            $table->integer('order')->default(0); // Thứ tự bài học trong chương
            $table->boolean('is_free_preview')->default(false); // Cho xem trước miễn phí
            $table->boolean('is_published')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('chapter_id');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};