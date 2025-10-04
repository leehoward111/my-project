<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('content');                // QR 內文（URL 或任意字串）
            $table->string('image_path')->nullable(); // 生成的 QR 圖片 URL
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('qrcodes');
    }
};
