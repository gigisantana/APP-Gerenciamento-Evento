@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold mb-4 text-lime-700">Gerenciar Eventos</h1>
    
    @if ($registro->isEmpty())
        <p>Você ainda não está vinculado a nenhum evento.</p>
    @else
        <ul class="space-y-4">
            @foreach ($inscricoes->sortBy('evento.data_inicio') as $inscricao)
            <div onclick="window.location.href='{{ route('evento.show', ['id' => $inscricao->evento->id]) }}'" 
                class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <li class="p-4 border rounded-lg">                    
                    <div class="flex gap-5">
                        <h2 class=" text-xl font-bold text-lime-700">{{ $inscricao->evento->nome }}</h2>
                        <span class="px-3 py-1 rounded-full text-white
                            @if ($inscricao->evento->status() === 'Encerrado') bg-gray-500
                            @elseif ($inscricao->evento->status() === 'Próximo') bg-yellow-500
                            @else bg-lime-500
                            @endif">
                            {{ $inscricao->evento->status() }}
                        </span>
                    </div>
                    <p>{{ $inscricao->evento->descricao }}</p>
                    <p class="text-sm text-gray-500">
                        Data: {{ $inscricao->evento->data_inicio->format('d/m/Y') }} - {{ $inscricao->evento->data_fim->format('d/m/Y') }}
                    </p>

                    @if ($inscricao->atividade)
                        <h3 class="text-lg font-medium mt-2">Atividades:</h3>
                        <ul class="list-disc ml-6">
                            <li>
                                <strong>{{ $inscricao->atividade->nome }}</strong> <br>
                                Data: {{ $inscricao->atividade->data->format('d/m/Y') }} <br>
                                Hora: {{ $inscricao->atividade->hora_inicio->format('H:i') }} - {{ $inscricao->atividade->hora_fim->format('H:i') }}
                            </li>
                        </ul>
                    @else
                        <p class="text-gray-500 mt-2">Você não está vinculado a nenhuma atividade deste evento.</p>
                    @endif
                </li>
            </div>
            @endforeach
        </ul>
    @endif
</div>
@endsection