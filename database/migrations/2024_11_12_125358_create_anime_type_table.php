<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('anime_type', function (Blueprint $table) {
            $table->id(); // ID для типа аниме
            $table->string('name'); // Название типа аниме, например, "Фильм" или "Сериал"
            $table->timestamps(); // Временные метки (created_at, updated_at)
        });
    }
    public function down()
    {
        Schema::dropIfExists('anime_type');
    }
};
