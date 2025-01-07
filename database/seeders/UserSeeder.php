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
                'nome' => 'Coordenador',
                'sobrenome' => 'User',
                'email' => 'coordenador@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Organizador',
                'sobrenome' => 'User',
                'email' => 'organizador@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Inscrito',
                'sobrenome' => 'User',
                'email' => 'inscrito@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'João',
                'sobrenome' => 'Silva',
                'email' => 'joao@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Maria',
                'sobrenome' => 'Oliveira',
                'email' => 'maria@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Carlos',
                'sobrenome' => 'Souza',
                'email' => 'carlos@ifpr.edu.br',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Ana',
                'sobrenome' => 'Lima',
                'email' => 'ana.lima@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Lucas',
                'sobrenome' => 'Costa',
                'email' => 'lucas.costa@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Beatriz',
                'sobrenome' => 'Almeida',
                'email' => 'beatriz.almeida@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Paulo',
                'sobrenome' => 'Ferreira',
                'email' => 'paulo.ferreira@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Fernanda',
                'sobrenome' => 'Pereira',
                'email' => 'fernanda.pereira@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'nome' => 'Rafael',
                'sobrenome' => 'Martins',
                'email' => 'rafael.martins@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
