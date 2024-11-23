<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterSeeder extends Seeder
{
    public function run()
    {
        DB::table('character')->insert([
            [
                'name' => 'Узумаки Наруто',
                'voice_actor' => 'Масаси Кисимото',
                'description' => 'Главный герой аниме. Наруто — ниндзя, который стремится стать сильнейшим и стать Хокаге.',
                'anime_id' => 1, // ID аниме "Наруто"
                'audio_path' => 'audio/characters/naruto.mp3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Учиха Саске',
                'voice_actor' => 'Нориаки Сугияма',
                'description' => 'Один из главных персонажей. Саске — член клана Учиха, одержимый местью за уничтожение его рода.',
                'anime_id' => 1, // ID аниме "Наруто"
                'audio_path' => 'audio/characters/sasuke.mp3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Харуно Сакура',
                'voice_actor' => 'Кана Ханадзавы',
                'description' => 'Дружит с Наруто и Саске. Является ученицей Тсунаде, и обладает сильными медицинскими навыками.',
                'anime_id' => 1, // ID аниме "Наруто"
                'audio_path' => 'audio/characters/sakura.mp3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Луффи',
                'voice_actor' => 'Майна Хисакава',
                'description' => 'Главный герой аниме "Ван-Пис". Луффи — пират, который стремится найти легендарное сокровище и стать королем пиратов.',
                'anime_id' => 2, // ID аниме "Ван-Пис"
                'audio_path' => 'audio/characters/luffy.mp3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Зоро',
                'voice_actor' => 'Сасаки Канэй',
                'description' => 'Мечник и верный друг Луффи. Зоро мечтает стать лучшим мечником в мире.',
                'anime_id' => 2, // ID аниме "Ван-Пис"
                'audio_path' => 'audio/characters/zoro.mp3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
