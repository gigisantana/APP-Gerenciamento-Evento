@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold mb-4">Minhas Inscrições</h1>
    
    @if ($inscricoes->isEmpty())
        <p>Você ainda não está inscrito em nenhum evento.</p>
    @else
        <ul class="space-y-4">
            @foreach ($inscricoes as $inscricao)
                <li class="p-4 border rounded-lg">
                    <h2 class="text-xl font-bold">{{ $inscricao->evento->nome }}</h2>
                    <p>{{ $inscricao->evento->descricao }}</p>
                    <p class="text-sm text-gray-500">
                        Data: {{ $inscricao->evento->data }} | Hora: {{ $inscricao->evento->hora_inicio }}
                    </p>
                    <p>
                        <span class="inline-block px-3 py-1 rounded-full text-white
                            @if ($inscricoes->evento->status === 'Encerrado') bg-gray-500
                            @elseif ($inscricoes->evento->status === 'Próximo') bg-yellow-500
                            @else bg-green-500
                            @endif">
                            {{ $inscricoes->evento->status }}
                        </span>
                    </p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection