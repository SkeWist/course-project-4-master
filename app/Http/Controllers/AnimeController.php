<?php

namespace App\Http\Controllers;

use App\Http\Request\Anime\AnimeListRequest;
use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnimeController extends Controller
{
    public function rules()
    {
        return [
            'genre' => 'nullable|string|exists:genres,name',
            'age_rating' => 'nullable|string|exists:age_ratings,name', // Проверка существования
            'sort_by' => 'nullable|string|in:title,release_date,rating',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
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
        $anime = Anime::with(['studio', 'ageRating', 'genres', 'animeType'])->find($animeId);

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
                'anime_type' => $anime->animeType?->name ?? 'Не указано',
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

        // Удаляем изображение, если оно существует
        if ($anime->image_url && Storage::disk('public')->exists($anime->image_url)) {
            Storage::disk('public')->delete($anime->image_url);
        }

        $anime->delete();

        return response()->json(['message' => 'Аниме успешно удалено.'], 200);
    }

    public function random()
    {
        // Получаем случайное аниме с помощью метода randomAnime
        $anime = Anime::randomAnime();


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
            'studio_id' => 'required|exists:studios,id',
            'age_rating_id' => 'required|exists:age_ratings,id',
            'anime_type_id' => 'nullable|exists:anime_types,id', // Проверка существования типа
            'episode_count' => 'required|integer|min:1',
            'rating' => 'required|numeric|min:0|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Поле изображения опционально
        ]);

        // Загрузка изображения, если оно было предоставлено
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('anime_images', 'public')
            : null;

        // Создание записи
        $anime = Anime::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'studio_id' => $request->input('studio_id'),
            'age_rating_id' => $request->input('age_rating_id'),
            'anime_type_id' => $request->input('anime_type_id'),
            'episode_count' => $request->input('episode_count'),
            'rating' => $request->input('rating'),
            'image_url' => $imagePath, // Сохраняем путь изображения или null
        ]);

        return response()->json(['message' => 'Аниме успешно добавлено!', 'anime_id' => $anime->id], 201);
    }
    public function editAnime(Request $request, $animeId)
    {
        $request->validate([
            'title' => 'nullable|string|min:3|max:255',
            'description' => 'nullable|string',
            'studio_id' => 'nullable|exists:studios,id',
            'age_rating_id' => 'nullable|exists:age_ratings,id',
            'anime_type_id' => 'nullable|exists:anime_types,id',
            'episode_count' => 'nullable|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Поле изображения опционально
        ]);

        $anime = Anime::find($animeId);

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        // Если предоставлено новое изображение, загружаем его
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно существует
            if ($anime->image_url && Storage::disk('public')->exists($anime->image_url)) {
                Storage::disk('public')->delete($anime->image_url);
            }

            $imagePath = $request->file('image')->store('anime_images', 'public');
            $anime->image_url = $imagePath;
        }

        // Обновляем остальные поля
        $anime->update($request->only([
            'title',
            'description',
            'studio_id',
            'age_rating_id',
            'anime_type_id',
            'episode_count',
            'rating',
        ]));

        return response()->json(['message' => 'Аниме успешно обновлено!']);
    }

}
