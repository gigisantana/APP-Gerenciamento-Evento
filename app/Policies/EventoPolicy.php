<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\Registro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EventoPolicy
{
    use HandlesAuthorization;

    public function gerenciarAtividades(User $user, Evento $evento)
    {
        // Verifica se o usuário é um organizador ou coordenador do evento
        return Registro::where('user_id', $user->id)
            ->where('evento_id', $evento->id)
            ->whereIn('role_id', [Role::where('nome', 'organizador')->value('id'), Role::where('nome', 'coordenador')->value('id')])
            ->exists();
    }
    
}
