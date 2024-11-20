<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AnimeTypeSeeder::class,   // Сначала сидер для типов аниме
            StudioSeeder::class,      // Сидер для студий
            AgeRatingSeeder::class,   // Сидер для возрастных рейтингов
            GenreSeeder::class,       // Сидер для жанров
            AnimeSeeder::class,       // Затем сидер для аниме
            CharacterSeeder::class,
            RoleSeeder::class,
            GallerySeeder::class,
        ]);
    }
}
