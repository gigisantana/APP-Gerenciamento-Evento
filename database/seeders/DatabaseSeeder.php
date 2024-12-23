<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            EventoSeeder::class,
        ]);

        // Associando papéis aos usuários
        $coordenador = \App\Models\User::where('email', 'coordenador@ifpr.edu.br')->first();
        $organizador = \App\Models\User::where('email', 'organizador@example.com')->first();
        $inscrito = \App\Models\User::where('email', 'inscrito@example.com')->first();

        $roleCoordenador = \App\Models\Role::where('nome', 'coordenador')->first();
        $roleOrganizador = \App\Models\Role::where('nome', 'organizador')->first();
        $roleInscrito = \App\Models\Role::where('nome', 'inscrito')->first();

        // Atribuir papéis ao usuário
        $coordenador->roles()->attach($roleCoordenador->id, ['evento_id' => 1]);
        $organizador->roles()->attach($roleOrganizador->id, ['evento_id' => 1]);
        $inscrito->roles()->attach($roleInscrito->id, ['evento_id' => 1]);
    }
}
