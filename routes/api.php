<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\CharacterController;

// Аутентификация
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Пользовательские данные
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/user/profile', [UserController::class, 'updateProfile']);

// Аниме
Route::get('/anime', [AnimeController::class, 'index']); // Получение списка аниме
Route::get('/anime/{animeId}', [AnimeController::class, 'show'])->name('anime.show'); // Просмотр конкретного аниме
Route::get('/anime/random', [AnimeController::class, 'random']); // Случайное аниме
Route::get('/anime/year/{year}', [AnimeController::class, 'getAnimeByYear']); // Поиск аниме по году
Route::get('/anime/search', [AnimeController::class, 'searchAnime']); // Поиск аниме по ключевым словам
Route::get('/anime-types', [AnimeController::class, 'getAnimeTypes']); // Типы аниме

// Дополнительные ресурсы
Route::get('/genres', [AnimeController::class, 'getGenres']); // Получение списка жанров
Route::get('/studios', [AnimeController::class, 'getStudios']); // Получение списка студий
Route::get('/age_ratings', [AnimeController::class, 'getAgeRatings']); // Возрастные рейтинги

// Администрирование (требуется аутентификация)
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Управление аниме
    Route::get('/anime', [AnimeController::class, 'index'])->name('admin.anime.index'); // Список аниме
    Route::post('/anime', [AnimeController::class, 'addAnime'])->name('admin.anime.add'); // Добавление нового аниме
    Route::get('/anime/{animeId}', [AnimeController::class, 'show'])->name('admin.anime.show'); // Просмотр аниме
    Route::put('/anime/{animeId}', [AnimeController::class, 'editAnime'])->name('admin.anime.update'); // Обновление аниме
    Route::delete('/anime/{animeId}', [AnimeController::class, 'destroy'])->name('admin.anime.destroy'); // Удаление аниме

    // Управление студиями
    Route::post('/studios', [StudioController::class, 'addStudio'])->name('admin.studio.add'); // Добавление студии
});
// Работа с персонажами
Route::get('anime/{animeId}/characters', [CharacterController::class, 'getCharacterAudioByAnime']);
