<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locais extends Model
{
    use HasFactory;

    protected $table = 'locais';
    protected $fillable = [
        'bloco',
        'espaco',
    ];

    public function atividades()
    {
        return $this->hasMany(Atividade::class, 'local_id');
    }
}