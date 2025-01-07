<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        //'password' => 'hashed',
    ];

    // Método para verificar domínio do e-mail
    public function isServidorIfpr()
    {
        return $this->email_verified_at !== null && str_ends_with($this->email, '@ifpr.edu.br');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permissions')
                    ->withPivot('evento_id')
                    ->withTimestamps();
    }

    public function hasRole( $roleName, $eventoId)
    {
        return $this->roles()
                ->wherePivot('evento_id', $eventoId)
                ->where('nome', $roleName)
                ->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles->flatMap->permissions->pluck('nome')->contains($permission);
    }

    public function registro()
    {
        return $this->belongsToMany(Evento::class, 'registro')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }
}
