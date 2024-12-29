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
            [
                'nome' => 'João Silva',
                'email' => 'joao@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Maria Oliveira',
                'email' => 'maria@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Carlos Souza',
                'email' => 'carlos@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Ana Lima',
                'email' => 'ana.lima@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Lucas Costa',
                'email' => 'lucas.costa@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Beatriz Almeida',
                'email' => 'beatriz.almeida@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Paulo Ferreira',
                'email' => 'paulo.ferreira@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Fernanda Pereira',
                'email' => 'fernanda.pereira@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Rafael Martins',
                'email' => 'rafael.martins@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
