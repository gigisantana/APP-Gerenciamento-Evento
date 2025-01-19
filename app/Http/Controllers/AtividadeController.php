<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Registro;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $evento = Evento::findOrFail($id);
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data' => ['required','date',
            function ($attribute, $value, $fail) use ($evento) {
                if ($value < $evento->data_inicio) {
                    $fail('A data da atividade não pode ser anterior à data de início do evento.');
                }},
            ],
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

    public function edit($id, $atividade_id)
{
    // Busca a atividade com o ID correto
    $atividade = Atividade::where('id', $atividade_id)
        ->where('evento_id', $id)
        ->first();

    // Verifica se a atividade foi encontrada
    if (!$atividade) {
        abort(404, 'Atividade não encontrada.');
    }

    // Garante que o evento está associado
    $evento = $atividade->evento;

    // Caso o evento não esteja associado
    if (!$evento) {
        abort(404, 'Evento associado à atividade não encontrado.');
    }

    return view('atividade.edit', compact('atividade', 'evento'));
}

    public function update(Request $request, $id, $atividade_id)
    {
        $atividade = Atividade::findOrFail($atividade_id)->load('evento');
        $evento = $atividade->evento;

        // dd($request->input('data'));
        // dd([
        //     'data_enviada' => $request->input('data'),
        //     'evento_data_inicio' => $evento->data_inicio,
        //     'evento_data_fim' => $evento->data_fim,
        // ]);

        $request->validate([
            'nome' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'data' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($evento) {
                    if ($value < $evento->data_inicio || $value > $evento->data_fim) {
                        $fail("A data da atividade deve estar entre {$evento->data_inicio->format('d/m/Y')} e {$evento->data_fim->format('d/m/Y')}.");
                    }
                },
            ],
            'hora_inicio' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'before_or_equal:hora_fim'],
            'hora_fim' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'after_or_equal:hora_inicio'],
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
        return redirect()->route('atividade.edit', ['id' => $id, 'atividade_id' => $atividade_id])
        ->with('success', 'Atividade atualizada com sucesso!');
    }

    public function destroy($id, $atividade_id)
    {
        $atividade = Atividade::findOrFail($atividade_id);
        $evento = Evento::findOrFail($id);

        $atividade->delete();
        return redirect()->route('evento.show', compact('evento', 'userRole'))->with('success', 'Atividade excluída com sucesso!');
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
