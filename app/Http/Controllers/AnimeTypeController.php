<?php

namespace App\Http\Controllers;

use App\Models\AnimeType;
use Illuminate\Http\Request;

class AnimeTypeController extends Controller
{
    public function index()
    {
        return response()->json(AnimeType::all());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:anime_types']);

        $type = AnimeType::create($request->all());

        return response()->json(['message' => 'Тип успешно создан!', 'data' => $type], 201);
    }
}
