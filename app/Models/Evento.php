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

    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions')
                    ->withPivot('role_id') // Inclui o papel do usuário no evento
                    ->withTimestamps();
    }
}
