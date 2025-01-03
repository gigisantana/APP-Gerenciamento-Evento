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

        // Atualiza o nome se o campo foi preenchido
        if ($request->filled('nome')) {
            $user->nome = $request->nome;
        }
    
        // Atualiza a senha se o campo foi preenchido
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
        $userId = auth()->id();
        $inscricoes = Registro::with('evento')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($inscricoes){
                $evento = $inscricoes->evento;

                $today = now();
                $dataEvento = $evento->data;

                if ($dataEvento < $today) {
                    $evento->status = 'Encerrado';
                } elseif ($dataEvento->diffInDays($today) <= 3) {
                    $evento->status = 'Próximo';
                } else {
                    $evento->status = 'Futuro!';
                }
                return $inscricoes;
            });

        if (isset($inscricoes)){
            return view('profile.inscricoes', compact('inscricoes'));
        }
        return "<h1>ERRO: NENHUMA INSCRIÇÃO ENCONTRADA!</h1>";
    }
}
