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
        $query = Anime::with(['genre', 'studio', 'ageRating']); // Загрузка связанных данных

        // Фильтры
        if ($request->filled('genre')) {
            $query->whereHas('genre', function ($q) use ($request) {
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
                'image_url' => "http://course-project-4-master/storage/" . $anime->image_url,
                'age_rating' => $anime->ageRating?->name ?? 'Не указано',
                'anime_type' => $anime->animeType?->name ?? 'Не указано',
                'episode_count' => $anime->episode_count,
            ],
        ]);
    }

    public function getAnimeByYear($year)
    {
        // Проверяем, есть ли аниме, выпущенные в указанном году
        $animeList = Anime::where('release_year', $year)->get();

        if ($animeList->isEmpty()) {
            return response()->json(['message' => "Аниме, выпущенные в $year году, не найдены."], 404);
        }

        // Формируем список аниме
        $animeData = $animeList->map(function ($anime) {
            $genres = $anime->genres ? $anime->genres->pluck('name') : [];
            return [
                'id' => $anime->id,
                'title' => $anime->title,
                'description' => $anime->description,
                'studio' => $anime->studio ? $anime->studio->name : null,
                'rating' => $anime->ageRating ? $anime->ageRating->name : null,
                'genres' => $genres, // теперь genres может быть пустым массивом
                'image_url' => asset('storage/' . $anime->image_url),
            ];
        });

        return response()->json($animeData, 200);
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


    public function searchAnime(Request $request)
    {
        // Получаем ключевое слово из параметров запроса
        $keyword = $request->query('keyword');

        // Проверяем, передано ли ключевое слово
        if (!$keyword) {
            return response()->json(['message' => 'Ключевое слово не указано.'], 400);
        }

        // Ищем аниме с использованием частичного совпадения (независимо от регистра)
        $animeList = Anime::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->get();

        // Проверяем, найдены ли аниме
        if ($animeList->isEmpty()) {
            return response()->json(['message' => "Аниме с ключевым словом \"{$keyword}\" не найдено."], 404);
        }

        // Формируем список найденных аниме
        $animeData = $animeList->map(function ($anime) {
            return [
                'id' => $anime->id,
                'title' => $anime->title,
                'description' => $anime->description,
                'studio' => $anime->studio ? $anime->studio->name : null,
                'rating' => $anime->ageRating ? $anime->ageRating->name : null,
                'genres' => $anime->genres ? $anime->genres->pluck('name') : [],
                'image_url' => $anime->image_url ? asset('storage/' . $anime->image_url) : null,
            ];
        });

        return response()->json($animeData, 200);
    }
    public function addAnime(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'studio_id' => 'required|exists:studio,id',
            'age_rating_id' => 'required|exists:age_rating,id',
            'anime_type_id' => 'nullable|exists:anime_type,id', // Проверка существования типа
            'episode_count' => 'required|integer|min:1',
            'rating' => 'required|numeric|min:0|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Поле изображения опционально
            'release_year' => 'required|string',
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
            'release_year' => $request->input('release_year'),
        ]);

        return response()->json(['message' => 'Аниме успешно добавлено!', 'anime_id' => $anime->id], 201);
    }
    public function editAnime(Request $request, $animeId)
    {
        // Валидация данных
        $request->validate([
            'title' => 'nullable|string|min:3|max:255',
            'description' => 'nullable|string',
            'studio_id' => 'nullable|exists:studio,id',
            'age_rating_id' => 'nullable|exists:age_rating,id',
            'anime_type_id' => 'nullable|exists:anime_type,id',
            'episode_count' => 'nullable|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Поле изображения опционально
        ]);

        // Поиск аниме
        $anime = Anime::find($animeId);

        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        // Обновляем поля модели только если они предоставлены в запросе
        if ($request->has('title')) $anime->title = $request->title;
        if ($request->has('description')) $anime->description = $request->description;
        if ($request->has('studio_id')) $anime->studio_id = $request->studio_id;
        if ($request->has('age_rating_id')) $anime->age_rating_id = $request->age_rating_id;
        if ($request->has('anime_type_id')) $anime->anime_type_id = $request->anime_type_id;
        if ($request->has('episode_count')) $anime->episode_count = $request->episode_count;
        if ($request->has('rating')) $anime->rating = $request->rating;

        // Если предоставлено новое изображение, загружаем его
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно существует
            if ($anime->image_url && Storage::disk('public')->exists($anime->image_url)) {
                Storage::disk('public')->delete($anime->image_url);
            }

            // Сохраняем новое изображение
            $imagePath = $request->file('image')->store('anime_images', 'public');
            $anime->image_url = $imagePath;
        }

        // Сохраняем изменения в базе данных
        $anime->save();

        return response()->json([
            'message' => 'Аниме успешно обновлено.',
            'anime' => $anime
        ], 200);
    }
    public function getAnimeGallery($animeId)
    {
        // Найти аниме по ID
        $anime = Anime::with('gallery')->find($animeId);

        // Проверить, существует ли аниме
        if (!$anime) {
            return response()->json(['message' => 'Аниме не найдено.'], 404);
        }

        // Получить список изображений из галереи
        $gallery = $anime->gallery->map(function ($item) {
            return [
                'id' => $item->id,
                'image_url' => asset('storage/' . $item->image_url),
                'description' => $item->description,
            ];
        });

        return response()->json([
            'anime' => $anime->title,
            'gallery' => $gallery,
        ], 200);
    }
}
