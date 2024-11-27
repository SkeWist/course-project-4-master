<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimeType extends Model
{
    use HasFactory;

    // Указываем, что модель работает с таблицей anime_types
    protected $table = 'anime_types';

    // Указываем, какие поля можно массово заполнять
    protected $fillable = ['name'];

    public function anime()
    {
        return $this->hasMany(Anime::class);
    }

    // Если необходимо кастомизировать типы данных для некоторых полей:
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Правила валидации, если модель используется для валидации данных
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
