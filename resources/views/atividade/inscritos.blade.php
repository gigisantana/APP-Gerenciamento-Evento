@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold mb-1 text-lime-500">
        {{ $atividade->evento->nome }} 
    </h1>
    <h2 class="text-xl font-semibold mb-4 text-lime-700">
        {{ $atividade->nome }}
    </h2>
    <p>Inscritos na atividade: </p>

    @if ($inscritos->isEmpty())
        <p>Nenhum usuário inscrito nesta atividade.</p>
    @else
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-lime-600 text-white">
                    <th class="px-4 py-2">Nome do Participante</th>
                    <th class="px-4 py-2">E-mail</th>
                    <th class="px-4 py-2">Data da Inscrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscritos as $registro)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $registro->user->nome }} {{ $registro->user->sobrenome }}</td>
                        <td class="px-4 py-2">{{ $registro->user->email }}</td>
                        <td class="px-4 py-2">
                            {{ $registro->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <div class="m-2 mt-6">
        <a href="{{ route('relatorio.pdf', [$atividade->evento->id, $atividade->id]) }}" class="justify-center bg-lime-500 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-lime-600">Baixar PDF</a>
    </div>
</div>
@endsection