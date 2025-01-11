<?php

namespace App\Http\Middleware;

use App\Models\Registro;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRoleEvento
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $eventoId = $request->route('id'); // ou 'evento', dependendo da sua rota
        $registro = Registro::where('evento_id', $eventoId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$registro || !in_array($registro->role->id, [1, 2])) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
