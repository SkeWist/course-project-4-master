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
        'title', 'description', 'studio_id', 'age_rating_id', 'anime_type_id',
        'episode_count', 'rating', 'image_url', 'release_year'
    ];

    protected $table = 'anime'; // Явно указываем таблицу

    /**
     * Связь с таблицей жанров (Many-to-Many).
     */
    public function genre()
    {
        return $this->belongsToMany(Genre::class, 'anime_genre', 'anime_id', 'genre_id');
    }

    /**
     * Связь с таблицей студий (One-to-Many).
     */
    public function studio()
    {
        return $this->belongsTo(Studio::class);  // Убедитесь, что Studio имеет правильное имя таблицы
    }

    /**
     * Связь с таблицей возрастных рейтингов (One-to-Many).
     */
    public function ageRating()
    {
        return $this->belongsTo(AgeRating::class);  // Убедитесь, что связь настроена правильно
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
    public function character()
    {
        return $this->hasMany(Character::class, 'anime_id');
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }
}
