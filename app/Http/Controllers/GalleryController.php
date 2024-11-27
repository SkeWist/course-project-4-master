<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Anime;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    // Получение всех изображений галереи
    public function index()
    {
        $gallery = Gallery::all();
        return response()->json($gallery, 200);
    }

    // Получение изображения галереи по ID
    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery, 200);
    }

    // Создание нового изображения галереи
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Валидация для файла изображения
            'anime_id' => 'required|exists:anime,id', // Валидация для anime_id
        ]);

        // Сохранение изображения в папку AnimeTitle
        $path = $request->file('image')->store('AnimeTitle', 'public');

        // Создание записи в базе данных
        $gallery = Gallery::create([
            'anime_id' => $request->anime_id,
            'image_url' => Storage::url($path), // Генерация публичного URL
        ]);

        return response()->json($gallery, 201);
    }

    // Удаление изображения галереи
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Удаление файла из хранилища
        if ($gallery->image_url) {
            $path = str_replace('/storage/', '', $gallery->image_url);
            Storage::disk('public')->delete($path);
        }

        $gallery->delete();
        return response()->json(['message' => 'Изображение галереи удалено.'], 200);
    }
    public function addGalleryImages(Request $request, $anime_id)
    {

        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        // Проверяем, существует ли аниме с таким ID
        $anime = Anime::findOrFail($anime_id);

        // Сохраняем изображения
        foreach ($request->file('images') as $image) {
            $path = $image->store('public/gallery_images');

            // Добавляем запись в базу данных
            Gallery::create([
                'anime_id' => $anime->id,
                'image_path' => $path,
            ]);
        }

        return response()->json(['message' => 'Изображения успешно загружены!']);
    }

    // Метод для получения изображений для конкретного аниме
    public function getGalleryImages($anime_id)
    {
        // Получаем все изображения для аниме с заданным ID
        $images = Gallery::where('anime_id', $anime_id)->get();

        // Если изображения есть, возвращаем их, иначе сообщение о том, что изображений нет
        if ($images->isEmpty()) {
            return response()->json(['message' => 'Изображения не найдены для этого аниме.'], 404);
        }

        return response()->json($images);
    }

}
