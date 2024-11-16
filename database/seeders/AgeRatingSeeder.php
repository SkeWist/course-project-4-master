<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeRatingSeeder extends Seeder
{
    public function run()
    {
        $ageRatings = ['3+', '12+', '16+', '18+'];

        foreach ($ageRatings as $rating) {
            DB::table('age_ratings')->insert([
                'name' => $rating,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
