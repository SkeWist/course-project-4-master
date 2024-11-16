<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Anime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    public function index(): JsonResponse
    {
        $characters = Character::all();
        return response()->json([
            'message' => 'Список персонажей получен.',
            'data'    => $characters,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $character = Character::find($id);

        if (!$character) {
            return response()->json(['message' => 'Персонаж не найден.'], 404);
        }

        return response()->json([
            'message' => 'Персонаж успешно получен.',
            'data'    => $character,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'voice_actor' => 'required|string|max:255',
            'description' => 'nullable|string',
            'anime_id'    => 'required|exists:animes,id',
            'audio_path'  => 'nullable|string', // Добавлено для аудиодорожек
        ]);

        $character = Character::create($validatedData);

        return response()->json([
            'message' => 'Персонаж успешно создан.',
            'data'    => $character,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'voice_actor' => 'required|string|max:255',
            'description' => 'nullable|string',
            'anime_id'    => 'required|exists:animes,id',
            'audio_path'  => 'nullable|string', // Добавлено для аудиодорожек
        ]);

        $character = Character::find($id);

        if (!$character) {
            return response()->json(['message' => 'Персонаж не найден.'], 404);
        }

        $character->update($validatedData);

        return response()->json([
            'message' => 'Персонаж успешно обновлен.',
            'data'    => $character,
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $character = Character::find($id);

        if (!$character) {
            return response()->json(['message' => 'Персонаж не найден.'], 404);
        }

        $character->delete();

        return response()->json([
            'message' => 'Персонаж успешно удалён.',
        ], 200);
    }

    public function getCharacterAudioByAnime($animeId): JsonResponse
    {
        // Проверяем, существует ли аниме
        $anime = Anime::with('characters')->find($animeId);

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        // Формируем список персонажей с их аудио-дорожками и другими параметрами
        $charactersWithAudio = $anime->characters->filter(function ($character) use ($animeId) {
            // Проверяем, что anime_id персонажа соответствует переданному animeId
            return $character->anime->contains('id', $animeId);
        })->map(function ($character) {
            // Получаем все аниме, в которых этот персонаж участвует
            $animeTitles = $character->anime->pluck('title')->implode(', ');

            return [
                'id'            => $character->id,
                'name'          => $character->name,
                'description'   => $character->description, // Описание персонажа
                'voice_actor'   => $character->voice_actor, // Голосовой актер
                'audio_url'     => $character->audio_path ? Storage::url($character->audio_path) : null, // Аудио-дорожка
                'anime_titles'  => $animeTitles, // Названия аниме, в которых этот персонаж участвует
            ];
        });

        return response()->json([
            'anime_title'    => $anime->title,
            'characters'     => $charactersWithAudio,
        ], 200);
    }
}
