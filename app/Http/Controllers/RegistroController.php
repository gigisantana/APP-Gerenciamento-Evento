<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Registro;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    
    public function addOrganizador(Request $request, $id)
    {
        $user = User::where('email', $request->email)->first();
        
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        if (!$user) {
            return back()->withErrors(['email' => 'Usuário com este e-mail não encontrado.']);
        }
        
        $registro = Registro::where('user_id', $user->id)
        ->where('evento_id', $id)
        ->first();

        if ($registro) {
            // Atualiza a role do registro existente
            $registro->update(['role_id' => 2]); // 2 = Organizador
        } else {
            Registro::create([
                'user_id' => $user->id,
                'evento_id' => $id,
                'role_id' => 2, // 2 = Organizador
                'atividade_id' => null,
            ]);
        }
        return redirect()->route('evento.show', $id)->with('message', 'Organizador vinculado com sucesso!');
    }

    public function inscritos($id, $atividade_id)
    {
        $inscritos = Registro::where('atividade_id', $atividade_id)
        ->with('user') // Carrega os dados do usuário
        ->get();

        $atividade = Atividade::with('evento')->findOrFail($atividade_id);

    return view('atividade.inscritos', compact('inscritos', 'atividade'));
    }

    public function exportarPDF($id, $atividade_id)
    {
        $atividade = Atividade::with('evento')->findOrFail($atividade_id);

    // Verifica se a atividade existe
    if (!$atividade) {
        return abort(404, 'Atividade não encontrada');
    }

    // Buscar todos os inscritos na atividade, incluindo dados do usuário
    $inscritos = Registro::where('atividade_id', $atividade_id)
        ->with('user')  // Carrega os dados do usuário
        ->get();

    // Gerar o PDF com a view e dados necessários
    $pdf = Pdf::loadView('atividade.relatorio', compact('inscritos', 'atividade'));

    // Nome do arquivo PDF com base no evento e atividade
    $nomeArquivo = "relatorio_evento_{$atividade->evento->id}_atividade_{$atividade_id}.pdf";

    // Baixar o PDF gerado
    return $pdf->download($nomeArquivo);
    }
}
