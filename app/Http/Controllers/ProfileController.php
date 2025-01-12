<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Registro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Atualiza o campo se foi preenchido
        if ($request->filled('nome')) {
            $user->nome = $request->nome;
        }
        if ($request->filled('sobrenome')) {
            $user->password = bcrypt($request->sobrenome);
        }
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function inscricoes()
    {
        $user = auth()->id();
        $roleInscrito = 3;

        $inscricoes = Registro::with('evento', 'atividade')
            ->where('user_id', $user)
            ->where('role_id', $roleInscrito)
            ->get()
            ->map(function ($inscricao){
                $evento = $inscricao->evento;

                if ($evento) {
                    $statusData = $evento->status();
                    $evento->status = $statusData['status'];
                    $evento->diasRestantes = $statusData['diasRestantes'];
                }
                return $inscricao;
            });
            return view('profile.inscricoes', compact('inscricoes'));       
    }
    
    public function gerenciar()
    {
        $user = auth()->id();
        $roles = [
            'organizador' => 2,
            'coordenador' => 1,
        ];

        // lista os ID dos eventos em que o usuário é coordenador, pra evitar duplicação no painel de gerenciamento
        $coordenadorEventosId = Registro::where('user_id', $user)
        ->where('role_id', $roles['coordenador'])
        ->pluck('evento_id');
    
        $vinculosOrganizador = Registro::with('evento', 'atividade')
            ->where('user_id', $user)
            ->where('role_id', $roles['organizador'])
            ->whereNotIn('evento_id', $coordenadorEventosId) // vai excluir os eventos que já estão na lista
            ->get()
            ->map(function ($vinculosOrganizador){
                $evento = $vinculosOrganizador->evento;
    
                    if ($evento) {
                        $statusData = $evento->status();
                        $evento->status = $statusData['status'];
                        $evento->diasRestantes = $statusData['diasRestantes'];
                    }
                    return $vinculosOrganizador;
                });
    
        $vinculosCoordenador = Registro::with('evento', 'atividade')
            ->where('user_id', $user)
            ->where('role_id', $roles['coordenador'])
            ->get()
            ->map(function ($vinculosCoordenador){
                $evento = $vinculosCoordenador->evento;
    
                    if ($evento) {
                        $statusData = $evento->status();
                        $evento->status = $statusData['status'];
                        $evento->diasRestantes = $statusData['diasRestantes'];
                    }
                    return $vinculosCoordenador;
                });
            return view('profile.gerenciamento', compact('vinculosOrganizador', 'vinculosCoordenador'));       
    }
}
