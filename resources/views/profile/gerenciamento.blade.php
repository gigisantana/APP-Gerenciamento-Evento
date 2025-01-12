@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold mb-4 text-lime-700">Gerenciar Eventos</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Coluna Organizador --}}
        <div>
            <h2 class="text-xl font-bold mb-4 text-lime-700">Eventos como Organizador</h2>
            
            @if ($vinculosOrganizador->isEmpty() || $vinculosCoordenador->isEmpty()) <!-- verificar se isso aqui funciona com não-servidores-->
                <p class="text-gray-600">Você ainda não é organizador de nenhum evento.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($vinculosOrganizador->sortBy('evento.data_inicio') as $vinculo)
                        <div onclick="window.location.href='{{ route('evento.show', ['id' => $vinculo->evento->id]) }}'" 
                            class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                            <li class="p-4 border rounded-lg">                    
                                <div class="flex gap-5">
                                    <h2 class="text-xl font-bold text-lime-700">{{ $vinculo->evento->nome }}</h2>
                                    <span class="px-3 py-1 rounded-full text-white
                                        @if ($vinculo->evento->status === 'Encerrado') bg-gray-500
                                        @elseif ($vinculo->evento->status === 'Próximo') bg-yellow-500
                                        @else bg-lime-500
                                        @endif">
                                        @if ($vinculo->evento->status === 'Próximo')
                                            Faltam {{ $vinculo->evento->diasRestantes }} dias!
                                        @else
                                            {{ $vinculo->evento->status }}
                                        @endif
                                    </span>
                                </div>
                                <p>{{ $vinculo->evento->descricao }}</p>
                                <p class="text-sm text-gray-500">
                                    Data: {{ $vinculo->evento->data_inicio->format('d/m/Y') }} - {{ $vinculo->evento->data_fim->format('d/m/Y') }}
                                </p>
                            </li>
                        </div>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Coluna Coordenador --}}
        @if (Auth::user()->is_servidor_ifpr)
            <div>                   
                <h2 class="text-xl font-bold mb-4 text-lime-700">Eventos como Coordenador</h2>
                @if ($vinculosCoordenador->isEmpty())
                        <p class="text-gray-600">Você ainda não é coordenador de nenhum evento.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($vinculosCoordenador->sortBy('evento.data_inicio') as $vinculo)
                            <div onclick="window.location.href='{{ route('evento.show', ['id' => $vinculo->evento->id]) }}'" 
                                class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                                <li class="p-4 border rounded-lg">                    
                                    <div class="flex gap-5">
                                        <h2 class="text-xl font-bold text-lime-700">{{ $vinculo->evento->nome }}</h2>
                                        <span class="px-3 py-1 rounded-full text-white
                                            @if ($vinculo->evento->status === 'Encerrado') bg-gray-500
                                            @elseif ($vinculo->evento->status === 'Próximo') bg-yellow-500
                                            @else bg-lime-500
                                            @endif">
                                            @if ($vinculo->evento->status === 'Próximo')
                                                Faltam {{ $vinculo->evento->diasRestantes }} dias!
                                            @else
                                                {{ $vinculo->evento->status }}
                                            @endif
                                        </span>
                                    </div>
                                    <p>{{ $vinculo->evento->descricao }}</p>
                                    <p class="text-sm text-gray-500">
                                        Data: {{ $vinculo->evento->data_inicio->format('d/m/Y') }} - {{ $vinculo->evento->data_fim->format('d/m/Y') }}
                                    </p>
                                </li>
                            </div>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection