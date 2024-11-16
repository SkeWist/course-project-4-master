<?php

namespace App\Http\Request\Character;

use App\Http\Request\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\FlareClient\Api;

class CharacterAudioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'character_id' => 'required|integer|exists:characters,id',
        ];
    }

    public function messages()
    {
        return [
            'character_id.required' => 'Идентификатор персонажа обязателен.',
            'character_id.integer'  => 'Идентификатор персонажа должен быть числом.',
            'character_id.exists'   => 'Персонаж с таким идентификатором не найден.',
        ];
    }
}
