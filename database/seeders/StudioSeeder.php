<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    public function run()
    {
        $studios = ['Studio Ghibli', 'Toei Animation', 'Madhouse', 'Sunrise', 'Bones', 'MAPPA'];

        foreach ($studios as $studio) {
            DB::table('studios')->insert([
                'name' => $studio,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
