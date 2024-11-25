<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anime_id'); // Ссылка на аниме
            $table->string('image_path'); // Путь к изображению
            $table->timestamps();
            // Добавляем внешний ключ, связывающий с таблицей аниме
            $table->foreign('anime_id')->references('id')->on('anime')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery');
    }
};
