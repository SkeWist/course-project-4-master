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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->foreignId('age_rating_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('anime_type_id')->nullable(); // или не nullable, если хотите, чтобы это поле всегда было заполнено
            $table->foreign('anime_type_id')->references('id')->on('anime_types')->onDelete('set null'); // Связь с таблицей anime_types
            $table->integer('episode_count');
            $table->decimal('rating', 3, 1);
            $table->string('image_url')->nullable(); // Добавляем поле для URL изображения
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
