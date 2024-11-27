<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        // Получаем всех пользователей
        $users = User::all();

        // Возвращаем JSON-ответ
        return response()->json([
            'message' => 'Список всех пользователей',
            'users' => $users,
        ]);
    }
    public function updateProfile(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed', // Новый пароль и подтверждение обязательны
        ]);

        // Проверка валидации
        if ($validator->fails()) {
            return response()->json(['message' => 'Ошибка валидации.', 'errors' => $validator->errors()], 422);
        }

        // Получаем текущего аутентифицированного пользователя
        $user = Auth::user();

        // Обновляем пароль
        $user->password = Hash::make($request->password); // Хешируем новый пароль

        // Сохраняем изменения
        $user->save();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Пароль успешно обновлён.'], 200);
    }
}
