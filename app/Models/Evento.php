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
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
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
}
