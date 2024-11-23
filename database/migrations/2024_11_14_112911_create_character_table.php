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
        Schema::create('character', function (Blueprint $table) {
            $table->id(); // Автоматически создаст столбец id
            $table->string('name'); // Название персонажа
            $table->string('voice_actor'); // Актер озвучивания
            $table->text('description'); // Описание персонажа
            $table->unsignedBigInteger('anime_id'); // Внешний ключ anime_id
            $table->foreign('anime_id') // Определяем внешний ключ
            ->references('id')    // Поле, на которое ссылаемся
            ->on('anime')         // Таблица, на которую ссылаемся
            ->onDelete('cascade'); // Удаляем персонажей при удалении аниме
            $table->string('audio_path')->nullable(); // Путь к аудио, может быть null
            $table->timestamps(); // Столбцы created_at и updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('character');
    }
};
