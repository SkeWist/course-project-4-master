<?php

namespace App\Http\Request\Auth;

use App\Http\Request\ApiRequest;

class LoginRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'login'    => 'required|string|exists:users,login',
            'password' => 'required|string|min:8',
        ];
    }
    public function messages(): array
    {
        return [
            'login.required'    => 'Поле "Логин" обязательно для заполнения.',
            'login.string'      => 'Поле "Логин" должно быть строкой.',
            'login.exists'      => 'Пользователь с таким логином не найден.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.string'   => 'Поле "Пароль" должно быть строкой.',
            'password.min'      => 'Пароль должен быть не менее :min символов.',
        ];
    }
}
