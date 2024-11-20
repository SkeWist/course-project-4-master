<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Получение всех изображений галереи
    public function index()
    {
        $galleries = Gallery::all();
        return response()->json($galleries, 200);
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
            'anime_id' => 'required|exists:animes,id', // Валидация для anime_id
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

    // Обновление изображения галереи
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Валидация для файла изображения
            'anime_id' => 'required|exists:animes,id', // Валидация для anime_id
        ]);

        if ($request->hasFile('image')) {
            // Удаление старого файла
            if ($gallery->image_url) {
                $oldPath = str_replace('/storage/', '', $gallery->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Сохранение нового изображения
            $path = $request->file('image')->store('AnimeTitle', 'public');
            $gallery->image_url = Storage::url($path);
        }

        // Обновление записи в базе данных
        $gallery->anime_id = $request->anime_id;
        $gallery->save();

        return response()->json($gallery, 200);
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
}
