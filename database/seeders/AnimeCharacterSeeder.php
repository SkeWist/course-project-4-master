<?php

namespace Database\Seeders;

use App\Models\Anime;
use App\Models\Character;
use Illuminate\Database\Seeder;

class AnimeCharacterSeeder extends Seeder
{
    public function run()
    {
        // Пример данных: связываем персонажей с аниме
        $characterAnimeData = [
            // id персонажа => [id аниме, id аниме, ...]
            1 => [1, 3],  // Персонаж с id 1 связан с аниме с id 1 и 3
            2 => [2],      // Персонаж с id 2 связан с аниме с id 2
            3 => [1, 4],   // Персонаж с id 3 связан с аниме с id 1 и 4
            4 => [3],      // Персонаж с id 4 связан с аниме с id 3
            5 => [2, 4],   // Персонаж с id 5 связан с аниме с id 2 и 4
        ];

        // Для каждого персонажа, связываем его с указанными аниме
        foreach ($characterAnimeData as $characterId => $animeIds) {
            $character = Character::find($characterId);

            // Если персонаж существует
            if ($character) {
                // Привязываем персонажа к аниме с указанными id
                $character->anime()->attach($animeIds);
            } else {
                $this->command->info("Персонаж с id {$characterId} не найден.");
            }
        }

        $this->command->info('Промежуточная таблица anime_character заполнена конкретными данными.');
    }
}
