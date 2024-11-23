<?php

namespace App\Http\Controllers;

use App\Http\Request\Auth\LoginRequest;
use App\Http\Request\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Создаем нового пользователя
        $user = User::create([
            'name'     => $validated['name'],
            'surname'  => $validated['surname'],
            'login'    => $validated['login'],
            'password' => Hash::make($validated['password']),
            'role_id'  => 2,  // Здесь также проверим, что связь с ролью настроена правильно
        ]);

        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован!',
            'user'    => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('login', 'password');

        $user = User::where('login', $credentials['login'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Генерация токена (если используется Sanctum или Passport)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Успешная авторизация',
                'token'   => $token,
                'user'    => $user
            ], 200);
        }

        return response()->json([
            'message' => 'Неверные данные'
        ], 401);
    }

    public function logout()
    {
        // Удаление текущего токена
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Выход успешен'
        ], 200);
    }
}
