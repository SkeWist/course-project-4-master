<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index() // Получение всех студий
    {
        $studios = Studio::all(); // Получаем все студии
        return response()->json($studios, 200);
    }

    public function show($id) // Получение студии по ID
    {
        $studio = Studio::findOrFail($id); // Находим студию по ID
        return response()->json($studio, 200);
    }

    public function store(Request $request) // Создание новой студии
    {
        $request->validate([
            'name' => 'required|string|min:3|max:32', // Валидация поля name
        ]);

        // Создаем новую студию с использованием массового назначения
        $studio = Studio::create($request->only(['name']));

        return response()->json([
            'message' => 'Студия успешно добавлена!',
            'studio_id' => $studio->id
        ], 201);
    }

    public function update(Request $request, $id) // Обновление студии
    {
        $request->validate([
            'name' => 'required|string|min:3|max:32', // Валидация для name
        ]);

        $studio = Studio::findOrFail($id); // Находим студию по ID
        $studio->update($request->only(['name'])); // Обновляем только поле name

        return response()->json([
            'message' => 'Студия успешно обновлена!',
            'studio_id' => $studio->id
        ], 200);
    }

    public function destroy($id) // Удаление студии
    {
        $studio = Studio::findOrFail($id); // Находим студию по ID
        $studio->delete(); // Удаляем студию

        return response()->json(['message' => 'Студия успешно удалена.'], 200);
    }
}

