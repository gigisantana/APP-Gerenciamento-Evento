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
        $inscricoes = Registro::with('evento', 'atividade')
            ->where('user_id', $user)
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
}
