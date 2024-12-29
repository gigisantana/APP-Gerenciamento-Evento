<?php

namespace Database\Seeders;

use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $roles = Role::all();
        $eventos = Evento::all();
        $atividades = Atividade::all();

        foreach ($eventos as $evento) {
            foreach ($atividades as $atividade) {
                $coordenadorCont = 0;
                $organizadorCont = 0;
                
                foreach ($users as $user) {
                    // Verificar se o e-mail do usuário termina com @ifpr.edu.br
                    if (str_ends_with($user['email'], '@ifpr.edu.br')) {
                        // Se o e-mail for @ifpr.edu.br, será coordenador ou organizador
                        if ($coordenadorCont == 0) {
                            // Atribui o coordenador (apenas 1 coordenador por evento)
                            DB::table('registro')->insert([
                                'evento_id' => $evento['id'],
                                'atividade_id' => $atividade->id, // Atribua a atividade correspondente
                                'user_id' => $user['id'],
                                'role_id' => 1, // Coordenador
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $coordenadorCont++;
                        } elseif ($organizadorCont < 3) {
                            // Atribui organizadores (máximo de 3 organizadores por evento)
                            DB::table('registro')->insert([
                                'evento_id' => $evento['id'],
                                'atividade_id' => $atividade->id, // Atribua a atividade correspondente
                                'user_id' => $user['id'],
                                'role_id' => 2, // Organizador
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $organizadorCont++;
                        }
                    } else {
                        // Atribui inscritos (qualquer usuário pode ser inscrito)
                        DB::table('registro')->insert([
                            'evento_id' => $evento['id'],
                            'atividade_id' => $atividade->id, // Atribua a atividade correspondente
                            'user_id' => $user['id'],
                            'role_id' => 3, // Inscrito
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
