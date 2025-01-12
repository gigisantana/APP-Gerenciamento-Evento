@extends('layouts.divider')
@section('content')
    <div class="container mx-auto px-4">
        <!-- Cabeçalho do Evento -->
        <div class="flex justify-between pb-2">
            @if($userRole === 1) {{-- Coordenador --}}
                <div class="mb-2">
                    <div>
                        <h3 class="text-gray-500 mb-1">Menu Evento</h3>
                    </div>
                    <div>
                        <a href="{{ route('evento.edit', ['id' => $evento->id]) }}" class=" justify-center bg-lime-500 text-white text-center px-4 py-2 my-4  mr-2 rounded-md hover:bg-lime-600">
                            Editar
                        </a>
                        <a href="{{ route('evento.destroy', ['id' => $evento->id]) }}" class=" justify-center bg-red-500 text-white text-center px-4 py-2 my-4 rounded-md hover:bg-red-600">
                            Excluir
                        </a>
                    </div>
                </div>
            @endif    
        </div>

        <div class="bg-lime-100 text-lime-700 text-center p-6 rounded-md shadow-md mb-2">
            <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
            <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
            <p class="mt-4">
                <strong>Data:</strong> {{ $evento->data_inicio }} - {{ $evento->data_fim }}
            </p>
        </div>

        <div>
            @if(($userRole === 1 || $userRole === 2)) {{-- Coordenador ou Organizador --}}
                <div class="mb-2">
                    <div class="">
                        <h3 class="text-gray-500 mb-1">Menu Atividade</h3>
                    </div>
                    <div>
                        <a href="{{ route('atividade.create', ['id' => $evento->id]) }}" class=" justify-center bg-lime-500 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-lime-600">
                            Criar
                        </a>
                        <a href="{{ route('atividade.edit', ['id' => $evento->id]) }}" class=" justify-center bg-orange-400 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-orange-500">
                            Editar
                        </a>
                        <a href="{{ route('atividade.destroy', ['id' => $evento->id]) }}" class=" justify-center bg-red-500 text-white text-center px-4 py-2 my-4 rounded-md hover:bg-red-600">
                            Excluir
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Atividades do Evento -->
        @if($evento->atividade)
        <div>
            @if($userRole === 1 || $userRole === 2)
            @endif
            <h2 class="text-2xl font-semibold text-lime-700 mb-4">Atividades</h2>
            @if($evento->atividades->count())
                <div class="flex flex-col gap-6">
                    @foreach ($evento->atividades as $atividade)
                    <div class="border border-lime-200 rounded-md p-4 shadow-md">
                        <h3 class="text-lg font-bold text-lime-700">{{ $atividade->nome }}</h3>
                        <p class="text-sm text-gray-600">{{ $atividade->descricao }}</p>
                        <p class="mt-2 text-sm text-gray-600">
                            <strong>Data:</strong> {{ $atividade->data->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Horário:</strong> {{ $atividade->hora_inicio }} - {{ $atividade->hora_fim }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Local:</strong>
                        </p>

                        <div class="flex justify-center items-center">
                        @auth
                            @php
                                $registro = $atividade->registro ? $atividade->registro->where('user_id', auth()->id())->first() : null;
                            @endphp

                            @if ($registro)
                                <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 mt-4 rounded-md">
                                    Inscrito!
                                </button>
                            @else
                                <form action="{{ route('registro.inscrever', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                    <input type="hidden" name="atividade_id" value="{{ $atividade->id }}">
                                    <input type="hidden" name="role_id" value="3"> <!-- Inscrito -->
                                    <button type="submit" class="bg-lime-500 text-white px-4 py-2 rounded-md hover:bg-lime-600">
                                        Inscrever-se
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class=" justify-center bg-lime-500 text-white text-center px-4 py-2 mt-4 rounded-md hover:bg-lime-600">
                                Inscrever-se
                            </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- modal de confirmação de inscrição -->
                {{-- <div class="fixed top-0 w-full z-50">
                    @if (session('message'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-md shadow-md mb-6">
                        {{ session('message') }}
                    </div>
                    @endif
                </div> --}}
            @else
                <p class="text-gray-500">Nenhuma atividade cadastrada para este evento.</p>
            @endif
        </div>
        @endif
    </div>
@endsection