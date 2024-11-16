<?php

namespace App\Http\Controllers;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index() // Получение всех изображений галереи
    {
        $galleries = Gallery::all();
        return response()->json($galleries, 200);
    }

    public function show($id) // Получение изображения галереи по ID
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery, 200);
    }

    public function store(Request $request) // Создание нового изображения галереи
    {
        $request->validate([
            'image_url' => 'required|url', // Валидация для image_url
            'anime_id' => 'required|exists:animes,id', // Валидация для anime_id
        ]);

        $gallery = Gallery::create($request->only(['anime_id', 'image_url'])); // Используем только те поля, которые в $fillable
        return response()->json($gallery, 201);
    }

    public function update(Request $request, $id) // Обновление изображения галереи
    {
        $request->validate([
            'image_url' => 'required|url', // Валидация для image_url
            'anime_id' => 'required|exists:animes,id', // Валидация для anime_id
        ]);

        $gallery = Gallery::findOrFail($id);
        $gallery->update($request->only(['anime_id', 'image_url'])); // Обновляем только те поля, которые в $fillable
        return response()->json($gallery, 200);
    }

    public function destroy($id) // Удаление изображения галереи
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        return response()->json(['message' => 'Изображение галереи удалено.'], 200);
    }
}
