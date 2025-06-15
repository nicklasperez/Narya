<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $defaultAvatar = config('app.url') . '/assets/default-avatar.png';

        $users = [
            [
                'username' => 'bernardo',
                'name' => 'Bernardo',
                'surname' => 'Gómez',
                'email' => 'bernardo@example.com',
                'password' => Hash::make('Password123!'),
                'birthdate' => '1990-06-12',
                'profile_picture' => $defaultAvatar,
            ],
            [
                'username' => 'joseluis',
                'name' => 'José Luis',
                'surname' => 'Fernández',
                'email' => 'joseluis@example.com',
                'password' => Hash::make('Password123!'),
                'birthdate' => '1988-09-20',
                'profile_picture' => $defaultAvatar,
            ],
            [
                'username' => 'javier',
                'name' => 'Javier',
                'surname' => 'Martínez',
                'email' => 'javier@example.com',
                'password' => Hash::make('Password123!'),
                'birthdate' => '1995-04-10',
                'profile_picture' => $defaultAvatar,
            ],
            [
                'username' => 'rocio',
                'name' => 'Rocío',
                'surname' => 'López',
                'email' => 'rocio@example.com',
                'password' => Hash::make('Password123!'),
                'birthdate' => '1997-11-03',
                'profile_picture' => $defaultAvatar,
            ],
            [
                'username' => 'jalberto',
                'name' => 'José Alberto',
                'surname' => 'Ruiz',
                'email' => 'jalberto@example.com',
                'password' => Hash::make('Password123!'),
                'birthdate' => '1993-02-28',
                'profile_picture' => $defaultAvatar,
            ],
            [
                'username' => 'santiago',
                'name' => 'Santiago',
                'surname' => 'Pérez',
                'email' => 'santiago@example.com',
                'password' => Hash::make('Password123!'),
                'birthdate' => '1992-12-15',
                'profile_picture' => $defaultAvatar,
            ],
        ];

        foreach ($users as $data) {
            User::create($data);
        }
    }
}
