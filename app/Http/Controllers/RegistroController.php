<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function inscrever(Request $request)
    {
        $user = auth()->user();

        // Verifica se já existe um registro para este evento e atividade
        $registroExistente = Registro::where('user_id', $user->id)
            ->where('atividade_id', $request->atividade_id)
            ->first();

        if ($registroExistente) {
            return redirect()->back()->with('message', 'Você já está vinculado a esta atividade!');
        }

        Registro::create([
            'user_id' => $user->id,
            'evento_id' => $request->evento_id,
            'atividade_id' => $request->atividade_id,
            'role_id' => 3, // Inscrito
        ]);
        return redirect()->back()->with('message', 'Inscrição realizada com sucesso!');
    }

}
