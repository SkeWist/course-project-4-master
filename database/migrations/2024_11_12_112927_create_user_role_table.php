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
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')  // Указываем имя таблицы явно
                ->onDelete('cascade'); // Связь с пользователем
            $table->foreignId('role_id')
                ->constrained('role') // Имя таблицы для роли будет определено автоматически
                ->onDelete('cascade'); // Связь с ролью
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role');
    }
};
