<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'nome' => 'Coordenador User',
                'email' => 'coordenador@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Organizador User',
                'email' => 'organizador@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Inscrito User',
                'email' => 'inscrito@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
