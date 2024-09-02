<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\evento;
use Dompdf\Dompdf;

class EventoController extends Controller
{
    public function index() 
    {
        $this->authorize('index', Evento::class);

        $data = Evento::all();
        return view('evento.index', compact(['data']));
    }

    public function create() 
    {
        $this->authorize('create', Evento::class);
        return view('evento.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Evento::class);

        if($request->hasFile('documento')){
            $evento = new Evento();
            $evento->nome = $request->nome;
            $evento->descricao = $request->descricao;
            $evento->save();

            $ext = $request->file('documento')->getClientOriginalExtension();
            $nome_arq = $evento->id . "_" . time() . "." . $ext; 
            $request->file('documento')->storeAs("public/", $nome_arq);
            $evento->url = $nome_arq;
            $evento->save();

            return redirect()->route('evento.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('show', Evento::class);

        $evento = Evento::find($id);
        if(isset($evento)) {
            return view('evento.show', compact(['evento']));
        }
        return "<h1>ERRO: EVENTO NÃO ENCONTRADO!</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('edit', Evento::class);

        $evento = Evento::find($id);
        if(isset($evento)) {
            return view('evento.edit', compact(['evento']));
        }
        return "<h1>ERRO: EVENTO NÃO ENCONTRADO!</h1>";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('edit', Evento::class);

        $evento = Evento::find($id);
        if(isset($evento)) {
            $evento->nome = $request->nome;
            $evento->descricao = $request->descricao;
            $evento->save();
            return redirect()->route('evento.index');
        }
        return "<h1>ERRO: EVENTO NÃO ENCONTRADO!</h1>";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('destroy', Evento::class);

        $evento = Evento::find($id);
        if(isset($evento)) {
            $evento->delete();
            return redirect()->route('evento.index');
        }
        
        return "<h1>ERRO: EVENTO NÃO ENCONTRADO!</h1>";
    }

    public function report() 
    {
        $data = Evento::all();

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('evento.pdf', compact('data')));
        $dompdf->render();
        $dompdf->stream("relatorio-horas-atividade.pdf", 
            array("Attachment" => false));
    }
}
