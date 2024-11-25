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
        Schema::create('anime', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('studio_id')->constrained("studio");
            $table->foreignId('age_rating_id')->constrained("age_rating");
            $table->foreignId('anime_type_id')->nullable()->constrained("anime_type");
            $table->integer('episode_count');
            $table->decimal('rating', 5, 2);
            $table->string('image_url')->nullable();
            $table->year('release_year'); // Убедитесь, что тип поля release_year правильный
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime');
    }
};
