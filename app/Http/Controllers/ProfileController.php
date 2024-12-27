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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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
        $incricoes = Registro::with('evento')
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

        if (isset($incricoes)){
            return view('profile.incricoes', compact('incricoes'));
        }
        return "<h1>ERRO: NENHUMA INSCRIÇÃO ENCONTRADA!</h1>";
    }
}
