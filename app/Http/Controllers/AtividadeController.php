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

    /**
     * Display the specified resource.
     */
    public function show($eventoId, $id)
    {
        $evento = Evento::findOrFail($eventoId);
        $atividade = $evento->atividades()->findOrFail($id);
        $userId = Auth::id();
        $userRole = Registro::userRoleEvento($userId, $evento->id);
            
        return view('atividade.show', compact('evento', 'atividade', 'userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $atividade = Atividade::find($id);
        return view('atividade.edit', compact(['atividade']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $atividade = Atividade::find($id);
            
        $atividade->nome = $request->nome;
        $atividade->descricao = $request->descricao;
        $atividade->save();
        return redirect()->route('atividade.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $atividade = Atividade::find($id);
        return redirect()->route('atividade.index');
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
