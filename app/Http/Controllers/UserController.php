<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:32',
            'surname' => 'required|string|min:3|max:32',
            'login' => 'required|string|min:3|max:32|unique:users,login,' . Auth::id(), // Уникальность login, кроме текущего пользователя
            'password' => 'nullable|string|min:6|confirmed', // Для пароля добавлена проверка на подтверждение (password_confirmation)
            'role_id' => 'nullable|exists:roles,id', // Роль существует в таблице roles
        ]);

        // Получаем текущего пользователя
        $user = Auth::user();

        // Обновление данных пользователя
        $user->name = $validated['name'];
        $user->surname = $validated['surname'];
        $user->login = $validated['login'];

        // Если пароль предоставлен, то обновляем его
        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        // Если роль предоставлена и есть доступ на изменение, обновляем роль
        if ($request->filled('role_id')) {
            // Проверка, имеет ли пользователь права изменять роль (например, только администраторы)
            // Эта проверка может быть реализована по-разному в зависимости от ваших требований
            if (Auth::user()->role_id === 1) { // Пример: только администратор может менять роль
                $user->role_id = $validated['role_id'];
            }
        }

        // Сохраняем изменения
        $user->save();

        return response()->json(['message' => 'Профиль успешно обновлён.'], 200);
    }

    // Метод для получения всех пользователей
    public function getAllUsers()
    {
        $users = User::all(); // Получаем всех пользователей

        return response()->json($users, 200);
    }
}
