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
            ['nome' => 'coordenador', 'descricao' => 'Coordena eventos e gerencia organizadores.'],
            ['nome' => 'organizador', 'descricao' => 'Gerencia atividades no evento.'],
            ['nome' => 'inscrito', 'descricao' => 'Participa e visualiza eventos/atividades.']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
