<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'data',
        'hora_inicio',
        'hora_fim',
        'local_id',
        'evento_id',
    ];

    protected $casts = [
        'data' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fim' => 'datetime:H:i',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function registro()
    {
        return $this->hasMany(Registro::class);
    }

    public function local()
    {
        return $this->belongsTo(Locais::class, 'local_id');
    }
    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function organizadores()
    {
        return $this->hasMany(Registro::class, 'atividade_id', 'id')
                ->where('role_id', 2)
                ->with('user');
    }
}
