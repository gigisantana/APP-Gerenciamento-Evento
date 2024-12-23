<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions')
                    // Inclui o evento na relação
                    ->withPivot('evento_id') 
                    ->withTimestamps();
    }
}
