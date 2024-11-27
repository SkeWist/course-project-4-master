<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\RandomAnime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AgeRatingController;
use App\Http\Controllers\AnimeTypeController;

// Аутентификация
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Пользовательские данные
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/user/profile', [UserController::class, 'updateProfile']);

// Аниме
Route::get('/anime', [AnimeController::class, 'index']); // Получение списка аниме
Route::get('/anime/{animeId}', [AnimeController::class, 'show']); // Просмотр конкретного аниме
Route::get('/anime/year/{year}', [AnimeController::class, 'getAnimeByYear']); // Поиск аниме по году
Route::get('/anime_types', [AnimeTypeController::class, 'index']); // Типы аниме
Route::get('/anime/year/{year}', [AnimeController::class, 'getAnimeByYear']);
Route::get('/anime/search', [AnimeController::class, 'searchAnime'])->name('anime.search');
Route::get('/anime/{id}/gallery', [AnimeController::class, 'getAnimeGallery']);

// Дополнительные ресурсы
Route::get('/genres', [GenreController::class, 'index']); // Получение списка жанров
Route::get('/studios', [StudioController::class, 'index']); // Получение списка студий
Route::get('/age_ratings', [AgeRatingController::class, 'index']); // Возрастные рейтинги

// Администрирование (требуется аутентификация)
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Управление аниме
    Route::post('/anime', [AnimeController::class, 'addAnime']); // Добавление нового аниме
    Route::post('/anime/{animeId}', [AnimeController::class, 'editAnime']); // Обновление аниме
    Route::delete('/anime/{animeId}', [AnimeController::class, 'deleteAnime']); // Удаление аниме
    Route::post('/gallery/{anime_id}/add', [GalleryController::class, 'addGalleryImages']); // Для загрузки изображений
    Route::get('/gallery/{anime_id}', [GalleryController::class, 'getGalleryImages']); // Для получения изображений
    Route::post('/characters', [CharacterController::class, 'createCharacter']);
    Route::get('/users', [UserController::class, 'index']);

    // Управление студиями
    Route::post('/studios', [StudioController::class, 'addStudio'])->name('admin.studio.add'); // Добавление студии
});
// Работа с персонажами
Route::get('anime/{animeId}/characters', [CharacterController::class, 'getCharacterAudioByAnime']);

Route::get('anime/far', [RandomAnime::class, 'getRandomAnime']);
