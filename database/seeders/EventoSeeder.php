<?php

namespace Database\Seeders;

use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventos = [
            [
                'nome' => 'Semana Acadêmica de Tecnologia',
                'descricao' => 'Uma semana repleta de palestras e workshops sobre tecnologia.',
                'data_inicio' => Carbon::now()->addDays(5),
                'data_fim' => Carbon::now()->addDays(10),
            ],
            [
                'nome' => 'Feira de Ciências IFPR',
                'descricao' => 'Apresentação de projetos científicos dos alunos.',
                'data_inicio' => Carbon::now()->addDays(15),
                'data_fim' => Carbon::now()->addDays(17),
            ],
        ];

        // Inserindo os dados no banco
        foreach ($eventos as $evento) {
            Evento::create($evento);
        }
    }
}
