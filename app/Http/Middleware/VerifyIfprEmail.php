<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIfprEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !str_ends_with($user->email, '@ifpr.edu.br')) {
            return response()->json(['error' => 'Acesso negado. Somente servidores do IFPR podem realizar esta ação.'], 403);
        }

        return $next($request);
    }
}
