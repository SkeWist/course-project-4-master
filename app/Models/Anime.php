<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'studio',
        'rating',
        'genre',
        'episode_count',
        'image_url',
        'anime_type', // Тип аниме теперь будет обычным полем
        'age_rating_id',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'anime_genre', 'anime_id', 'genre_id');
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function ageRating()
    {
        return $this->belongsTo(AgeRating::class);
    }
    public function characters()
    {
        return $this->hasMany(Character::class, 'anime_id');
    }
}
