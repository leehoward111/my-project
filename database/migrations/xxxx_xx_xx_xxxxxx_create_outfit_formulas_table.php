<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('outfit_formulas', function (Blueprint $table) {
            $table->id();
            $table->string('emotion_sequence'); // 如: "正向、正向、正向、正向"
            $table->string('category'); // Positive, Neutral, Negative, Mixed-正中...
            $table->string('top'); // 上衣
            $table->string('bottom'); // 下身
            $table->string('accessory'); // 配件
            $table->text('explanation'); // 解釋原因
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('outfit_formulas');
    }
};
