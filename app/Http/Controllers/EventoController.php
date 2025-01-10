<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Registro;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function index() 
    {
        $evento = Evento::all();
        return view('evento.index', compact('evento'));
    }

    public function create()
    { 
        if (Auth::user()->isServidorIfpr()) {
            return view('evento.create');        
        } else {
            abort(403, 'Acesso restrito a servidores do IFPR.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'documento' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $evento = Evento::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
        ]);
        
        if($request->hasFile('documento')){
            $ext = $request->file('documento')->getClientOriginalExtension();
            $nome_arq = $evento->id . "_" . now()->timestamp . "." . $ext; 
            $request->file('documento')->storeAs("public/", $nome_arq);
            $evento->url = $nome_arq;
            $evento->save();
        }

            Registro::create([
                'user_id' => auth()->id(),
                'evento_id' => $evento->id,
                'role_id' => 1, // id da role "coordenador"
                'atividade_id' => null,
            ]);

            return redirect()->route('evento.show', $evento->id)->with('success', 'Evento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        $userId = Auth::id();
        $userRole = Registro::userRoleEvento($userId, $evento->id);


        return view('evento.show', compact('evento', 'userRole'));
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
        $dompdf->stream("relatorio-horas-evento.pdf", 
            array("Attachment" => false));
    }

    public function addOrganizador(Request $request, Evento $evento)
    {
        // Autoriza apenas coordenadores a vincular organizadores
        $this->authorize('addOrganizador', $evento);

        // Verifica se o usuário com o e-mail informado existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuário com este e-mail não encontrado.']);
        }

        // Vincula o usuário a role de organizador
        $role = Role::where('nome', 'organizador')->first();
        if ($role) {
            Registro::updateOrCreate([
                'user_id' => $user->id,
                'evento_id' => $evento->id,
                'role_id' => $role->id,
            ]);
        }
        return back()->with('success', 'Organizador vinculado com sucesso!');
    }

    public function eventosProximos()
    {
        $eventosProximos = Evento::where('data_inicio', '>=', Carbon::today())
        ->orderBy('data_inicio', 'asc')
        ->get();

    return view('dashboard', compact('eventosProximos'));
    }


}
