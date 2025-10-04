<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('emotions', function (Blueprint $table) {
            $table->id();
            // 若專案已有 users 表，保留外鍵；若沒有，把 constrained() 改成 ->nullable() 再自己加 index()
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('emotion', 32);          // happy / sad / surprised / ...
            $table->decimal('confidence', 5, 2)->nullable(); // 0.00 ~ 1.00
            $table->string('time', 16)->nullable(); // 例如 00:40
            $table->timestamps();                   // created_at / updated_at
        });
    }

    public function down(): void {
        Schema::dropIfExists('emotions');
    }
};
