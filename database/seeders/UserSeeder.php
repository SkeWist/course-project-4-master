<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'surname' => 'User',
                'login' => 'admin',
                'password' => Hash::make('password123'), // Зашифрованный пароль
                'role_id' => 1, // Предположим, что 1 — это роль admin
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular',
                'surname' => 'User',
                'login' => 'user',
                'password' => Hash::make('password123'), // Зашифрованный пароль
                'role_id' => 2, // Предположим, что 2 — это роль user
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
