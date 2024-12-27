<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evento_id',
        'role_id', // coordenador, organizador, inscrito
        'atividade_id',
    ];

    // Relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relação com o evento
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Relação com o cargo/papel
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function atividade()
    {
        return $this->belongsTo(Atividade::class, 'atividade_id');
    }
}
