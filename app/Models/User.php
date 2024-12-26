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

    public function registros()
    {
        return $this->belongsToMany(Evento::class, 'registros')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }
}
