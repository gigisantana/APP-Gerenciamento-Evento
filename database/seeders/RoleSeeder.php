<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nome' => 'coordenador', 'descricao' => 'Coordena eventos e gerencia organizadores.'], // id 1
            ['nome' => 'organizador', 'descricao' => 'Gerencia atividades no evento.'], // id 2
            ['nome' => 'inscrito', 'descricao' => 'Participa e visualiza eventos/atividades.'] // id 3
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
