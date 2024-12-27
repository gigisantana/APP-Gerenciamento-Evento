<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atividade;
use App\Models\Evento;
use Dompdf\Dompdf;

class AtividadeController extends Controller
{
    public function index($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);
        $atividade = $evento->atividade;
        return view('atividade.index', compact('evento', 'atividade'));
    }

    public function create(Request $request, Evento $evento)
    {
        $this->authorize('manageActivities', $evento);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $atividade = new Atividade([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'data' => $request->data,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'evento_id' => $evento->id,
        ]);
        $atividade->save();

        return response()->json([
            'message' => 'Atividade criada com sucesso!',
            'atividade' => $atividade,
        ]);
    }


    public function store(Request $request)
    {
        $this->authorize('create', Atividade::class);

        if($request->hasFile('documento')){
            $atividade = new Atividade();
            $atividade->nome = $request->nome;
            $atividade->descricao = $request->descricao;
            $atividade->hora_inicio = $request->hora_inicio;
            $atividade->hora_fim = $request->hora_fim;
            $atividade->evento_id = $request->evento_id;
            $atividade->save();

            $ext = $request->file('documento')->getClientOriginalExtension();
            $nome_arq = $atividade->id . "_" . time() . "." . $ext; 
            $request->file('documento')->storeAs("public/", $nome_arq);
            $atividade->url = $nome_arq;
            $atividade->save();

            return redirect()->route('atividade.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($eventoId, $id)
    {
        $evento = Evento::findOrFail($eventoId);
        $atividade = $evento->atividade()->findOrFail($id);
        if(isset($atividade)) {
            return view('atividade.show', compact('evento', 'atividade'));
        }
        return "<h1>ERRO: ATIVIDADE NÃO ENCONTRADO!</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('edit', Atividade::class);

        $atividade = Atividade::find($id);
        if(isset($atividade)) {
            return view('atividade.edit', compact(['atividade']));
        }
        return "<h1>ERRO: ATIVIDADE NÃO ENCONTRADO!</h1>";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('edit', Atividade::class);

        $atividade = Atividade::find($id);
        if(isset($atividade)) {
            $atividade->nome = $request->nome;
            $atividade->descricao = $request->descricao;
            $atividade->save();
            return redirect()->route('atividade.index');
        }
        return "<h1>ERRO: ATIVIDADE NÃO ENCONTRADO!</h1>";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('destroy', Atividade::class);

        $atividade = Atividade::find($id);
        if(isset($atividade)) {
            $atividade->delete();
            return redirect()->route('atividade.index');
        }
        
        return "<h1>ERRO: ATIVIDADE NÃO ENCONTRADO!</h1>";
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
