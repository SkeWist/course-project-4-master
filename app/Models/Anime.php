<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Anime extends Model
{
    use HasFactory;

    // Указываем заполняемые поля
    protected $fillable = [
        'title',
        'description',
        'studio_id',        // ID студии
        'age_rating_id',    // ID возрастного рейтинга
        'anime_type_id',    // ID типа аниме
        'episode_count',
        'rating',
        'image_url',        // URL изображения
    ];

    protected $table = 'animes'; // Явно указываем таблицу

    /**
     * Связь с таблицей жанров (Many-to-Many).
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'anime_genre', 'anime_id', 'genre_id');
    }

    /**
     * Связь с таблицей студий (One-to-Many).
     */
    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studio_id');
    }

    /**
     * Связь с таблицей возрастных рейтингов (One-to-Many).
     */
    public function ageRating()
    {
        return $this->belongsTo(AgeRating::class, 'age_rating_id');
    }

    /**
     * Связь с таблицей типов аниме (One-to-Many).
     */
    public function animeType()
    {
        return $this->belongsTo(AnimeType::class, 'anime_type_id');
    }

    /**
     * Связь с таблицей персонажей (One-to-Many).
     */
    public function characters()
    {
        return $this->hasMany(Character::class, 'anime_id');
    }

    /**
     * Получение случайного аниме.
     */
    public static function randomAnime()
    {
        return self::inRandomOrder()->first();
    }
}
