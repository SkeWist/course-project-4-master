<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $genres = ['Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Romance', 'Sci-Fi'];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
