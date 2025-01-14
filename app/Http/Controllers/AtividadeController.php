<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Registro;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;

class AtividadeController extends Controller
{
    public function index($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);
        $atividade = $evento->atividade;
        return view('atividade.index', compact('evento', 'atividade'));
    }

    public function create(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        return view('atividade.create', compact('evento'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
        ]);
    
        $atividade = Atividade::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'data' => $request->data,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'evento_id' => $id,
        ]);
    
        Registro::create([
            'user_id' => auth()->id(),
            'evento_id' => $id,
            'role_id' => 2, // id da role "organizador"
            'atividade_id' => $atividade->id,
        ]);

        return redirect()->route('evento.show', $id)
            ->with('success', 'Atividade criada com sucesso!');
    }

    public function show($eventoId, $id)
    {
        $evento = Evento::findOrFail($eventoId);
        $atividade = $evento->atividades()->findOrFail($id);
        $userId = Auth::id();
        $userRole = Registro::userRoleEvento($userId, $evento->id);
            
        return view('atividade.show', compact('evento', 'atividade', 'userRole'));
    }

    public function edit($id)
    {
        $atividade = Atividade::find($id);
        return view('atividade.edit', compact(['atividade']));
    }

    public function update(Request $request, $id)
    {
        $atividade = Atividade::find($id);

        $request->validate([
            'nome' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'data' => 'nullable|date|before_or_equal:data_fim',
            'hora_inicio' => 'nullable|time|before_or_equal:hora_fim',
            'hora_fim' => 'nullable|time|after_or_equal:hora_inicio',
        ]);
            
        if ($request->filled('nome')) {
            $atividade->nome = $request->nome;
        }
        if ($request->filled('descricao')) {
            $atividade->descricao = $request->descricao;
        }
        if ($request->filled('data')) {
            $atividade->data = $request->data;
        }
        if ($request->filled('hora_inicio')) {
            $atividade->hora_inicio = $request->hora_inicio;
        }
        if ($request->filled('hora_fim')) {
            $atividade->hora_fim = $request->hora_fim;
        }
        if ($request->filled('evento_id')) {
            $atividade->evento_id = $request->evento_id;
        }
    
        $atividade->save();
        return redirect()->route('atividade.index')->with('success', 'Atividade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $atividade = Atividade::findOrFail($id);

        $atividade->delete();
        return redirect()->route('atividade.index')->with('success', 'Evento excluído com sucesso!');
    }

    public function report() 
    {
        $data = Atividade::all();

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('atividade.pdf', compact('data')));
        $dompdf->render();
        $dompdf->stream("relatorio-horas-atividade.pdf", 
            array("Attachment" => false));
    }
}
