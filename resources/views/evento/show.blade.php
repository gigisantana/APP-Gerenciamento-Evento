@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Cabeçalho do Evento -->
        <div class="bg-lime-200 text-lime-700 text-center p-6 rounded-md shadow-md mb-6">
            <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
            <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
            <p class="mt-4">
                <strong>Data:</strong> {{ $evento->data_inicio->format('d/m/Y') }} - {{ $evento->data_fim->format('d/m/Y') }}
            </p>
        </div>

        <!-- Atividades do Evento -->
        <div>
            <h2 class="text-2xl font-semibold text-green-700 mb-4">Atividades</h2>
            @if($evento->atividades->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($evento->atividades as $atividade)
                    <div class="border border-gray-300 rounded-md p-4 shadow-md">
                        <h3 class="text-lg font-bold">{{ $atividade->nome }}</h3>
                        <p class="text-sm text-gray-600">{{ $atividade->descricao }}</p>
                        <p class="mt-2 text-sm">
                            <strong>Data:</strong> {{ $atividade->data->format('d/m/Y') }}
                        </p>
                        <p class="text-sm">
                            <strong>Horário:</strong> {{ $atividade->hora_inicio }} - {{ $atividade->hora_fim }}
                        </p>

                        @auth
                            @php
                                $registro = $atividade->registro ? $atividade->registro->where('user_id', auth()->id())->first() : null;
                            @endphp

                            @if ($registro)
                                <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 mt-4 rounded-md">
                                    Inscrito!
                                    <!-- Já inscrito como {{ $registro->role->nome }} -->
                                </button>
                            @else
                                <form action="{{ route('registro.inscrever', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                    <input type="hidden" name="atividade_id" value="{{ $atividade->id }}">
                                    <input type="hidden" name="role_id" value="3"> <!-- Inscrito -->
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                        Inscrever-se
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block bg-red-500 text-white text-center px-4 py-2 mt-4 rounded-md hover:bg-red-600">
                                Inscrever-se
                            </a>
                        @endauth
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Nenhuma atividade cadastrada para este evento.</p>
            @endif
        </div>
    </div>
@endsection