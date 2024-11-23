<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // Имя
            $table->string('surname');     // Фамилия
            $table->string('login')->unique();   // Логин, уникальный
            $table->string('password');    // Пароль
            $table->foreignId('role_id')->constrained('role'); // Внешний ключ для роли
            $table->timestamps();          // Время создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
