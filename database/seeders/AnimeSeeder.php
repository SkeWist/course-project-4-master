<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AnimeSeeder extends Seeder
{
    public function run()
    {
        // Вставка данных в таблицу "animes"
        DB::table('animes')->insert([
            [
                'title' => 'My Neighbor Totoro',
                'studio_id' => 1,  // Убедитесь, что студия с ID 1 существует
                'age_rating_id' => 2, // Убедитесь, что возрастной рейтинг с ID 2 существует
                'anime_type_id' => 1, // Убедитесь, что тип аниме с ID 1 существует (например, Movie)
                'description' => 'A heartwarming tale of childhood and friendship with a magical forest spirit.',
                'episode_count' => 1,
                'rating' => 8.5,
                'image_url' => 'https://example.com/images/totoro.jpg', // Добавлено поле image_url
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'One Piece',
                'studio_id' => 2,  // Убедитесь, что студия с ID 2 существует
                'age_rating_id' => 3, // Убедитесь, что возрастной рейтинг с ID 3 существует
                'anime_type_id' => 2, // Убедитесь, что тип аниме с ID 2 существует (например, TV Series)
                'description' => 'A young pirate embarks on an epic adventure to find the legendary One Piece treasure.',
                'episode_count' => 1000,
                'rating' => 9.0,
                'image_url' => 'https://example.com/images/onepiece.jpg', // Добавлено поле image_url
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Вставка данных в таблицу "anime_genre" (связь многие ко многим)
        DB::table('anime_genre')->insert([
            [
                'anime_id' => 1, // My Neighbor Totoro
                'genre_id' => 1, // Убедитесь, что жанр с ID 1 существует (например, Family)
            ],
            [
                'anime_id' => 1, // My Neighbor Totoro
                'genre_id' => 2, // Убедитесь, что жанр с ID 2 существует (например, Fantasy)
            ],
            [
                'anime_id' => 2, // One Piece
                'genre_id' => 3, // Убедитесь, что жанр с ID 3 существует (например, Adventure)
            ],
            [
                'anime_id' => 2, // One Piece
                'genre_id' => 4, // Убедитесь, что жанр с ID 4 существует (например, Action)
            ],
        ]);
    }
}
