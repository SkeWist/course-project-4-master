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
        Schema::create('characters', function (Blueprint $table) {
            $table->id(); // Автоматически создаст столбец id
            $table->string('name'); // Название персонажа
            $table->string('voice_actor'); // Актер озвучивания
            $table->text('description'); // Описание персонажа
            $table->foreignId('anime_id')->constrained()->onDelete('cascade'); // Связь с аниме (внешний ключ)
            $table->string('audio_path')->nullable(); // Путь к аудио, может быть null
            $table->timestamps(); // Столбцы created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
