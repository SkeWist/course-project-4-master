<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anime_genre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->constrained('anime')->cascadeOnDelete(); // Явно указываем имя таблицы anime
            $table->foreignId('genre_id')->constrained('genre')->cascadeOnDelete(); // Явно указываем имя таблицы genres
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anime_genre');
    }
};
