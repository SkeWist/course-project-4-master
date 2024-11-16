<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimeTypesTable extends Migration
{
    /**
     * Выполнить миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anime_types', function (Blueprint $table) {
            $table->id(); // ID для типа аниме
            $table->string('name'); // Название типа аниме, например, "Фильм" или "Сериал"
            $table->timestamps(); // Временные метки (created_at, updated_at)
        });
    }

    /**
     * Откатить миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anime_types');
    }
}
