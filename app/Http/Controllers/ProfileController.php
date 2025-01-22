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
    
    public function gerenciar(Request $request)
    {
        $user = auth()->id();
        $roles = [
            'organizador' => 2,
            'coordenador' => 1,
        ];

        // Receber o status da requisição, com o valor padrão sendo 'todos'
        $statusFiltro = $request->input('status', 'todos');

        // Lista os IDs dos eventos em que o usuário é coordenador
        $coordenadorEventosId = Registro::where('user_id', $user)
            ->where('role_id', $roles['coordenador'])
            ->pluck('evento_id');

        // Consulta base para organizadores
        $vinculosOrganizadorQuery = Registro::with('evento')
            ->where('user_id', $user)
            ->where('role_id', $roles['organizador'])
            ->whereNotIn('evento_id', $coordenadorEventosId);

        // Consulta base para coordenadores
        $vinculosCoordenadorQuery = Registro::with('evento')
            ->where('user_id', $user)
            ->where('role_id', $roles['coordenador']);

        // Aplicar filtro de status, se necessário
        $applyStatusFilter = function ($query) use ($statusFiltro) {
            if ($statusFiltro !== 'todos') {
                $query->whereHas('evento', function ($subQuery) use ($statusFiltro) {
                    $subQuery->where(function ($statusQuery) use ($statusFiltro) {
                        switch ($statusFiltro) {
                            case 'proximo':
                                $statusQuery->where('data_inicio', '>', today())
                                    ->where('data_inicio', '<=', today()->addDays(30));
                                break;
                            case 'acontecendo':
                                $statusQuery->whereDate('data_inicio', '<=', today())
                                    ->whereDate('data_fim', '>=', today());
                                break;
                            case 'encerrado':
                                $statusQuery->where('data_fim', '<', today());
                                break;
                            case 'futuro':
                                $statusQuery->where(function ($futureQuery) {
                                    $futureQuery->where('data_inicio', '>', today()->addDays(30))
                                        ->orWhereNull('data_inicio');
                                });
                                break;
                            case 'falta_1_dia':
                                $statusQuery->whereDate('data_inicio', '=', today()->addDay());
                                break;
                        }
                    });
                });
            }
        };

        $applyStatusFilter($vinculosOrganizadorQuery);
        $applyStatusFilter($vinculosCoordenadorQuery);

        // Obter registros de organizadores agrupados por evento
        $vinculosOrganizador = $vinculosOrganizadorQuery->get()
            ->groupBy('evento_id')
            ->map(function ($registros) {
                $evento = $registros->first()->evento;
                if ($evento) {
                    $statusData = $evento->status();
                    $evento->status = $statusData['status'];
                    $evento->diasRestantes = $statusData['diasRestantes'];
                }
                return $evento;
            });

        // Obter registros de coordenadores agrupados por evento
        $vinculosCoordenador = $vinculosCoordenadorQuery->get()
            ->groupBy('evento_id')
            ->map(function ($registros) {
                $evento = $registros->first()->evento;
                if ($evento) {
                    $statusData = $evento->status();
                    $evento->status = $statusData['status'];
                    $evento->diasRestantes = $statusData['diasRestantes'];
                }
                return $evento;
            });

        // Retornar a view com os dados filtrados
        return view('profile.gerenciamento', [
            'vinculosOrganizador' => $vinculosOrganizador,
            'vinculosCoordenador' => $vinculosCoordenador,
            'statusFiltro' => $statusFiltro,
        ]);
    }
}
