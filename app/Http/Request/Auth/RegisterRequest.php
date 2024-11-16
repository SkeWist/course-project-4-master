<?php

namespace App\Http\Request\Auth;

use App\Http\Request\ApiRequest;
use Spatie\FlareClient\Api;

class RegisterRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|min:2|max:255',
            'surname'     => 'required|string|min:2|max:255',
            'login'    => 'required|string|min:2|max:64|regex:/^[a-zA-Z0-9_-]+$/|unique:users',
            'password' => 'required|string|min:8|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'    => 'Поле "Имя" обязательно для заполнения.',
            'name.string'      => 'Поле "Имя" должно быть строкой.',
            'name.min'         => 'Поле "Имя" должно содержать не менее :min символов.',
            'name.max'         => 'Поле "Имя" должно содержать не более :max символов.',

            'surname.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'surname.string'   => 'Поле "Фамилия" должно быть строкой.',
            'surname.min'      => 'Поле "Фамилия" должно содержать не менее :min символов.',
            'surname.max'      => 'Поле "Фамилия" должно содержать не более :max символов.',

            'login.required'   => 'Поле "Логин" обязательно для заполнения.',
            'login.string'     => 'Поле "Логин" должно быть строкой.',
            'login.min'        => 'Поле "Логин" должно содержать не менее :min символов.',
            'login.max'        => 'Поле "Логин" должно содержать не более :max символов.',
            'login.regex'      => 'Поле "Логин" может содержать только латинские буквы, цифры, дефисы и подчеркивания.',
            'login.unique'     => 'Пользователь с таким логином уже существует.',

            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.string'   => 'Поле "Пароль" должно быть строкой.',
            'password.min'      => 'Поле "Пароль" должно содержать не менее :min символов.',
            'password.max'      => 'Поле "Пароль" должно содержать не более :max символов.',
        ];
    }
}
