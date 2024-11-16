<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use Illuminate\Http\Request;

class AgeRatingController extends Controller
{
    public function index() // Получение всех возрастных рейтингов
    {
        $ratings = AgeRating::all();
        return response()->json($ratings, 200);
    }

    public function show($id) // Получение возрастного рейтинга по ID
    {
        $rating = AgeRating::findOrFail($id);
        return response()->json($rating, 200);
    }

    public function store(Request $request) // Создание нового возрастного рейтинга
    {
        $request->validate([
            'name' => 'required|string|max:3'
        ]);

        $rating = AgeRating::create($request->all());
        return response()->json($rating, 201);
    }

    public function update(Request $request, $id) // Обновление возрастного рейтинга
    {
        $request->validate([
            'name' => 'required|string|max:3'
        ]);

        $rating = AgeRating::findOrFail($id);
        $rating->update($request->all());
        return response()->json($rating, 200);
    }

    public function destroy($id) // Удаление возрастного рейтинга
    {
        $rating = AgeRating::findOrFail($id);
        $rating->delete();
        return response()->json(['message' => 'Возрастной рейтинг удалён.'], 200);
    }
}
