<?php

namespace Database\Seeders;

use App\Models\Atividade;
use App\Models\Evento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AtividadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventos = Evento::all();

        foreach ($eventos as $evento) {
            // Criar 3 atividades para cada evento
            for ($i = 1; $i <= 3; $i++) {
                Atividade::create([
                    'nome' => "Atividade $i do evento {$evento->nome}",
                    'descricao' => "Descrição da atividade $i.",
                    'data' => $evento->data_inicio->copy()->addDays($i),
                    'hora_inicio' => '09:00:00',
                    'hora_fim' => '12:00:00',
                    'evento_id' => $evento->id,
                ]);
            }
        }
    }
}
