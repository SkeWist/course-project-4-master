<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'voice_actor',
        'description',
        'audio_path', // путь к звуковой дорожке
    ];

    // Отношение персонажа к аниме через промежуточную таблицу anime_character
    public function anime()
    {
        return $this->belongsToMany(Anime::class, 'anime_character');
    }
}
