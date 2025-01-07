<!-- Página de dashboard de eventos, com mapa e uma lista simples de eventos próximos -->
@extends('layouts.app')
@section('content')
<div class="align-top flex flex-wrap divide-x-2 divide-dashed divide-lime-500">
    <div class="p-6 basis-2/5">
        <h1 class="text-2xl font-bold text-lime-700 mb-4">Eventos do IFPR</h1>
        <!-- COLOCAR AQUI A LÓGICA PRA APARECER OS EVENTOS PRÓXIMOS!!! -->
        <p class="text-gray-500">Aqui você encontra os eventos próximos Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore dicta voluptate ducimus non necessitatibus, neque expedita fugiat quos. Quam fugiat quisquam eaque tempora vero repellat, officia ducimus minima at dolores? </p>
        <div class="grid grid-rows-1 md:grid-rows-3 lg:grid-rows-4 gap-6 my-6">
            @foreach($eventosProximos as $evento)
            <div onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'" 
                class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="bg-lime-50 shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-green-700">{{ $evento->nome }}</h2>
                        <p class="text-sm text-gray-600 mt-2">{{ $evento->descricao }}</p>
                        <p class="text-sm text-gray-500 mt-2">Data: {{ $evento->data_inicio->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="p-6 basis-3/5 px-8"> 
        <h1 class="text-2xl font-bold text-lime-700 ">Mapa do Campus Paranaguá</h1>
        <img src="{{ asset('images/mapa-campus.svg') }}" alt="Mapa do Campus" class="mapa-campus w-4/5 justify-self-center">
    </div>
</div>
@endsection
