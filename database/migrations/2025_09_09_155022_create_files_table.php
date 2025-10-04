<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('filepath');
            $table->enum('filetype', ['image', 'video']);
            $table->bigInteger('filesize'); // bytes
            $table->timestamps();
            
            $table->index(['user_id', 'filetype']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};