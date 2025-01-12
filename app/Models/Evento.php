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
        return $this->belongsToMany(User::class, 'permissions')
                    ->withPivot('role_id') // Inclui o papel do usuário no evento
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

        if (!$dataEvento) {
            return [
                'status' => 'Futuro',
                'diasRestantes' => null
            ];
        } elseif ($dataEvento < $today) {
            return [
                'status' => 'Encerrado',
                'diasRestantes' => 0
            ];
        } else {
            $diasRestantes = $today->diffInDays($dataEvento, false);
            if ($diasRestantes <= 30) {
                return [
                    'status' => 'Próximo',
                    'diasRestantes' => $diasRestantes
                ];
            } else {
                return [
                    'status' => 'Futuro',
                    'diasRestantes' => $diasRestantes
                ];
            }
        }
    }

    public function getUserRole(User $user)
    {
        $registro = $this->registros()
        ->where('user_id', $user->id)
        ->first();

        return $registro ? $registro->role_id : null;
    }
}
