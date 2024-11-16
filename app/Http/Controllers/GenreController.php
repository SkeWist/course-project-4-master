<?php

namespace App\Http\Controllers;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index() // Получение всех жанров
    {
        $genres = Genre::all();
        return response()->json($genres, 200);
    }

    public function show($id) // Получение жанра по ID
    {
        $genre = Genre::findOrFail($id);
        return response()->json($genre, 200);
    }

    public function store(Request $request) // Создание нового жанра
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $genre = Genre::create($request->all());
        return response()->json($genre, 201);
    }

    public function update(Request $request, $id) // Обновление жанра
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update($request->all());
        return response()->json($genre, 200);
    }

    public function destroy($id) // Удаление жанра
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return response()->json(['message' => 'Жанр удалён.'], 200);
    }
}
