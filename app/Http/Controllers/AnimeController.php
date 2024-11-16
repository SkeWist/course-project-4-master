<?php

namespace App\Http\Controllers;

use App\Http\Request\Anime\AnimeListRequest;
use App\Models\Anime;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function index(AnimeListRequest $request)
    {
        $query = Anime::with(['genres', 'studio', 'ageRating']); // Загрузка связанных данных

        // Фильтры
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('name', $request->input('genre'));
            });
        }

        if ($request->filled('studio')) {
            $query->whereHas('studio', function ($q) use ($request) {
                $q->where('name', $request->input('studio'));
            });
        }

        if ($request->filled('age_rating')) {
            $query->whereHas('ageRating', function ($q) use ($request) {
                $q->where('name', $request->input('age_rating'));
            });
        }

        // Сортировка
        $allowedSortFields = ['title', 'release_date', 'rating'];
        if ($request->filled('sort_by') && in_array($request->sort_by, $allowedSortFields)) {
            $query->orderBy($request->sort_by, $request->get('sort_order', 'asc'));
        }

        // Пагинация
        $perPage = $request->get('per_page', 15);
        $anime = $query->paginate($perPage);

        return response()->json($anime);
    }

    public function show($animeId)
    {
        $anime = Anime::with(['characters', 'studio', 'genres', 'ageRating'])->find($animeId);

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        return response()->json([
            'anime' => [
                'id' => $anime->id,
                'title' => $anime->title,
                'description' => $anime->description,
                'studio' => $anime->studio?->name ?? 'Не указано',
                'rating' => $anime->rating,
                'genres' => $anime->genres->pluck('name'),
                'image_url' => $anime->image_url,
                'age_rating' => $anime->ageRating?->name ?? 'Не указано',
                'anime_type' => $anime->anime_type,
                'release_date' => $anime->release_date,
                'episode_count' => $anime->episode_count,
            ],
        ]);
    }

    public function deleteAnime($animeId)
    {
        $anime = Anime::find($animeId);

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        $anime->delete();

        return response()->json(['message' => 'Аниме успешно удалено.'], 200);
    }

    public function random()
    {
        $anime = Anime::inRandomOrder()->first();

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        return response()->json($anime);
    }

    public function searchAnime(Request $request)
    {
        $keyword = $request->query('keyword');

        $anime = Anime::where('title', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->get();

        return response()->json($anime);
    }

    public function addAnime(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'studio' => 'required|string|min:3|max:32',
            'rating' => 'required|string|min:2|max:3',
            'image_url' => 'required|url',
            'age_rating_id' => 'required|exists:age_ratings,id',
            'anime_type' => 'required|string|in:Фильм,Сериал,Короткометражка',
            'episode_count' => 'required|integer|min:1',
        ]);

        $anime = Anime::create($request->all());

        return response()->json(['message' => 'Аниме успешно добавлено!', 'anime_id' => $anime->id], 201);
    }

    public function editAnime(Request $request, $animeId)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'studio' => 'required|string|min:3|max:32',
            'rating' => 'required|string|min:2|max:3',
            'image_url' => 'required|url',
            'age_rating_id' => 'required|exists:age_ratings,id',
            'anime_type' => 'required|string|in:Фильм,Сериал,Короткометражка',
            'episode_count' => 'nullable|integer|min:1',
        ]);

        $anime = Anime::find($animeId);

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        $anime->update($request->all());

        return response()->json(['message' => 'Аниме успешно обновлено!']);
    }
}
