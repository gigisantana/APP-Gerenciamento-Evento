<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Locais;
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
        $locais = Locais::all();
        return view('atividade.index', compact('evento', 'atividade', 'locais'));
    }

    public function create(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        $atividade = $evento->atividade;
        $locais = Locais::all();
        return view('atividade.create', compact('evento', 'locais'));
    }

    public function store(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $request->validate([
            'nome' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'data' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($evento) {
                    if ($value >= $evento->data_inicio && $value <= $evento->data_fim ) {
                        return true;
                    } else {
                        $fail("A data da atividade deve estar entre {$evento->data_inicio->format('d/m/Y')} e {$evento->data_fim->format('d/m/Y')}.");
                    }
                },
            ],
            'hora_inicio' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'before_or_equal:hora_fim'],
            'hora_fim' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'after_or_equal:hora_inicio'],
        ]);

        // Recupera o valor de local_id (bloco-espaco)
        $localId = $request->input('local_id');

        // Divide o valor de local_id em bloco e espaço
        list($bloco, $espaco) = explode('-', $localId);

        $local = Locais::where('bloco', $bloco)->where('espaco', $espaco)->first();

        if (!$local) {
            return redirect()->back()->withErrors('Local não encontrado!');
        }

        $atividade = Atividade::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'data' => $request->data,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'local_id' => $local->id,
            'evento_id' => $id,
        ]);

        Registro::create([
            'user_id' => auth()->id(),
            'evento_id' => $id,
            'role_id' => 2, // id da role "organizador"
            'atividade_id' => $atividade->id,
        ]);

        return redirect()->route('evento.show', ['id' => $evento->id])
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
        $atividade = Atividade::where('id', $atividade_id)
            ->where('evento_id', $id)
            ->with('locais')
            ->first();
        $locais = Locais::all();

        if (!$atividade) {
            abort(404, 'Atividade não encontrada.');
        }

        // Garante que o evento está associado
        $evento = $atividade->evento;
        if (!$evento) {
            abort(404, 'Evento associado à atividade não encontrado.');
        }

        return view('atividade.edit', compact('atividade', 'evento', 'locais'));
    }

    public function update(Request $request, $id, $atividade_id)
    {
        $atividade = Atividade::findOrFail($atividade_id);
        $evento = $atividade->evento;
        //dd($request->all());

        if ($request->has('local_id')) {
            [$bloco, $espaco] = explode('-', $request->local_id);
            $local = Locais::where('bloco', $bloco)->where('espaco', $espaco)->first();
    
            if (!$local) {
                return back()->withErrors(['local_id' => 'O local selecionado é inválido.']);
            }
            // Adicione o ID real do local ao request
            $request->merge(['local_id' => $local->id]);
        }
        if ($request->filled('local_id')) {
            $atividade->local_id = $request->local_id;
        }
        

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
                    $dataEnviada = \Carbon\Carbon::parse($value)->startOfDay();
                    $dataInicio = \Carbon\Carbon::parse($evento->data_inicio)->startOfDay();
                    $dataFim = \Carbon\Carbon::parse($evento->data_fim)->startOfDay();
                
                    if ($dataEnviada->lt($dataInicio) || $dataEnviada->gt($dataFim)) {
                        $fail("A data da atividade deve estar entre {$dataInicio->format('d/m/Y')} e {$dataFim->format('d/m/Y')}.");
                    }
                }
            ],
            'hora_inicio' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'before_or_equal:hora_fim'],
            'hora_fim' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/', 'after_or_equal:hora_inicio'],
            'local_id' => 'nullable|exists:locais,id',
        ]);
        // dd([
        //     'hora_inicio_request' => $request->hora_inicio,
        //     'hora_fim_request' => $request->hora_fim,
        //     'hora_inicio_model' => $atividade->hora_inicio,
        //     'hora_fim_model' => $atividade->hora_fim,
        // ]);
            
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
    
        // dd([
        //     'local_id_no_request' => $request->local_id,
        //     'local_resolved_id' => $local->id ?? null,
        //     'atividade_local_id' => $atividade->local_id,
        // ]);
        //dd($atividade);
        $atividade->save();
        return redirect()->route('atividade.edit', ['id' => $id, 'atividade_id' => $atividade_id])
        ->with('success', 'Atividade atualizada com sucesso!');
    }

    public function destroy($id, $atividade_id)
    {
        $atividade = Atividade::findOrFail($atividade_id);
        $evento = Evento::findOrFail($id);

        $atividade->delete();
        return redirect()->route('evento.show', $evento->id)
        ->with('success', 'Atividade deletada com sucesso!');
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
