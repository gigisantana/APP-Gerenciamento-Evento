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
        'evento_id',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function registro()
    {
        return $this->hasMany(Registro::class);
    }
}
