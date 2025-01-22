<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }

    public function registro()
    {
        return $this->hasMany(Registro::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Registro::class, 'evento_id', 'id', 'id', 'user_id')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function status()
    {
        $today = today();
        $dataEvento = $this->data_inicio;
        $dataFim = $this->data_fim;
        $diasRestantes = $today->diffInDays($dataEvento, false);

        if (!$dataEvento || $diasRestantes >= 30) {
            // Status: Próximo (sem data definida ou daqui a mais de 30 dias)
            return [
                'status' => 'Futuro',
                'diasRestantes' => $diasRestantes >= 30 ? $diasRestantes : null
            ];
        } else if ($dataEvento < $today && $dataFim < $today) {
            // Status: Encerrado
            return [
                'status' => 'Encerrado',
                'diasRestantes' => 0
            ];
        } else if ($dataEvento->diffInDays($today, false) == 1) {
            // Status: Falta 1 dia
            return [
                'status' => 'Falta 1 dia!',
                'diasRestantes' => 1
            ];
        } else if ($dataEvento <= $today && $dataFim >= $today) {
            // Status: Acontecendo
            return [
                'status' => 'Acontecendo!!',
                'diasRestantes' => 0
            ];
        } else {
            // Status: Próximo (casos restantes)
            return [
                'status' => 'Próximo',
                'diasRestantes' => $diasRestantes
            ];
        }
    }

    public function getUserRole(User $user)
    {
        $registro = $this->registros()
        ->where('user_id', $user->id)
        ->first();

        return $registro ? $registro->role_id : null;
    }

    public function coordenador()
    {
        return $this->hasOne(Registro::class, 'evento_id', 'id')
                    ->where('role_id', 1) // Supondo que 1 seja o papel de coordenador
                    ->with('user');
    }
}
