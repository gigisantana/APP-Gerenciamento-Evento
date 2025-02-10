<!-- Página de home de eventos, com mapa e uma lista simples de eventos próximos -->
@extends('layouts.divider')
@section('content')
        <h1 class="text-2xl font-bold text-lime-700">Eventos do IFPR</h1>
        <!-- COLOCAR AQUI A LÓGICA PRA APARECER OS EVENTOS PRÓXIMOS!!! -->
        <p class="text-gray-500">Aqui você encontra os eventos próximos que serão realizados no IFPR Campus Paranaguá. </p>
        <p class="text-gray-500">Clique no card do evento para obter mais detalhes. </p>
        <div class="grid grid-rows-1 md:grid-rows-3 lg:grid-rows-4 gap-6 my-6 mx-2">
            @foreach($eventosProximos as $evento)
            <div onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'" 
                class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="bg-lime-50 shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-green-700">{{ $evento->nome }}</h2>
                        <p class="text-sm text-gray-600 mt-2">{{ $evento->descricao }}</p>
                        <p class="text-sm text-gray-500 mt-2">Data: {{ $evento->data_inicio->format('d/m/Y') }} - {{ $evento->data_fim->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
@endsection
