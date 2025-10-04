<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('filename');             // 檔名
            $table->string('filepath');             // 相對或絕對路徑（如 uploads/photos/xxx.jpg）
            $table->string('filetype', 32)->nullable(); // image / video / other
            $table->unsignedBigInteger('filesize')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('files');
    }
};
