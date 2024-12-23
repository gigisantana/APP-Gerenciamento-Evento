<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atividade;
use App\Models\Evento;
use Dompdf\Dompdf;

class AtividadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);
        $atividade = $evento->atividade;
        return view('atividade.index', compact('evento', 'atividade'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($eventoId)
    {
        $this->authorize('index', Atividade::class);
        if (auth()->user()->hasRole('organizador', $eventoId)) {
            $atividade = Atividade::all();
            return view('atividade.create', compact('atividade'));
        } else {
            abort(403, 'Acesso negado.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
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
