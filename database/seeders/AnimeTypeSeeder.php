<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimeTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('anime_types')->insert([
            ['name' => 'Фильм'],
            ['name' => 'Сериал'],
            ['name' => 'OVA'],
            ['name' => 'OVA-сериал'],
        ]);
    }
}
