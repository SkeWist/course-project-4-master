<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;

class RandomAnime extends Controller
{
    public function getRandomAnime()
    {
        // Получаем случайное аниме из базы данных
        $anime = Anime::All();

        // Формируем ответ с данными о случайном аниме
        $animeData = [
            'id' => $anime->id,
            'title' => $anime->title,
            'description' => $anime->description,
            'studio' => $anime->studio ? $anime->studio->name : null,  // Студия, если связь настроена
            'rating' => $anime->ageRating ? $anime->ageRating->name : null,  // Возрастной рейтинг
            'genres' => $anime->genres->pluck('name'),  // Список жанров
            'characters' => $anime->characters->map(function ($character) {
                return [
                    'name' => $character->name,
                    'description' => $character->description,
                    'voice_actor' => $character->voice_actor,
                ];
            }),
            'image_url' => asset('storage/' . $anime->image_url),  // URL изображения
        ];
        return response()->json($animeData, 200);
    }
}
