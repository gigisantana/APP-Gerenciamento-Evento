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
    public function index(Request $request) 
    {
        $evento = Evento::all();
        return view('evento.index', compact('evento'));
    }

    public function create()
    { 
        if (Auth::user()->is_servidor_ifpr) {
            return view('evento.create');        
        } else {
            abort(403, 'Acesso restrito a servidores do IFPR.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
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

            return redirect()->route('evento.show', $evento->id)->with('message', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $evento = Evento::with([
            'atividades.local',
            'coordenador.user', // Coordenador com dados do usuário
            'atividades.organizadores.user' // Responsáveis com dados do usuário
        ])->findOrFail($id);
        $userId = Auth::id();
        $userRole = Registro::userRoleEvento($userId, $evento->id);
        //dd($evento->atividades);
        //dd($evento);
        //dd($evento->coordenador);

        return view('evento.show', compact('evento', 'userRole'));
    }

    public function edit($id)
    {
        $evento = Evento::findOrFail($id);
        return view('evento.edit', compact('evento'));
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        
            $request->validate([
                'nome' => 'nullable|string|max:255',
                'descricao' => 'nullable|string',
                'data_inicio' => 'nullable|date|before_or_equal:data_fim',
                'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            ]);

            if ($request->filled('nome')) {
                $evento->nome = $request->nome;
            }
            if ($request->filled('descricao')) {
                $evento->descricao = $request->descricao;
            }
            if ($request->filled('data_inicio')) {
                $evento->data_inicio = $request->data_inicio;
            }
            if ($request->filled('data_fim')) {
                $evento->data_fim = $request->data_fim;
            }
            $evento->save();

            return redirect()->route('evento.show', $evento->id)->with('message', 'Evento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);

        $evento->delete();
        return redirect()->route('home')->with('message', 'Evento excluído com sucesso!');
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

    public function eventosProximos()
    {
        $eventosProximos = Evento::where('data_inicio', '>=', Carbon::today())
        ->orderBy('data_inicio', 'asc')
        ->get();

        return view('home', compact('eventosProximos'));
    }
}
