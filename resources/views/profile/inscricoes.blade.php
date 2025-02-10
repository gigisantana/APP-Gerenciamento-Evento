@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-semibold mb-4 text-lime-700">Minhas Inscrições</h1>

        <div class="m-4">
            <form method="GET" action="{{ route('profile.inscricoes') }}">
                <x-input-label for="status" class="block text-sm font-medium text-gray-700" value="Filtrar por Status:"/>
                <select name="status" id="status" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm" onchange="this.form.submit()">
                    <option value="todos" {{ $statusFiltro == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="proximo" {{ $statusFiltro == 'proximo' ? 'selected' : '' }}>Próximos</option>
                    <option value="acontecendo" {{ $statusFiltro == 'acontecendo' ? 'selected' : '' }}>Acontecendo</option>
                    <option value="encerrado" {{ $statusFiltro == 'encerrado' ? 'selected' : '' }}>Encerrados</option>
                    <option value="falta_1_dia" {{ $statusFiltro == 'falta_1_dia' ? 'selected' : '' }}>Falta 1 dia</option>
                    <option value="futuro" {{ $statusFiltro == 'futuro' ? 'selected' : '' }}>Futuros</option>
                </select>
            </form>
        </div>
        @if ($inscricoes->isEmpty())
            <p>Você ainda não está inscrito em nenhum evento.</p>
        @else
        <ul class="space-y-4">
            @foreach ($inscricoes->sortBy('data_inicio') as $evento)
            <div onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'" 
                class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <li class="p-4 border rounded-lg">
                    <div class="flex gap-5 items-center">
                        <h2 class="text-xl font-bold text-lime-700">{{ $evento->nome }}</h2>
                        <span class="px-3 py-1 rounded-full text-white
                            @if ($evento->status === 'Encerrado') bg-gray-500
                            @elseif ($evento->status === 'Próximo' || $evento->status === 'Falta 1 dia!') bg-orange-500
                            @elseif ($evento->status === 'Acontecendo!!') bg-yellow-500
                            @else bg-lime-500
                            @endif">
                            @if ($evento->status === 'Próximo' && $evento->diasRestantes == 1)
                                Falta 1 dia!
                            @elseif ($evento->status === 'Próximo')
                                Faltam {{ $evento->diasRestantes }} dias!
                            @else
                                {{ $evento->status }}
                            @endif
                        </span>
                    </div>
                    <p>{{ $evento->descricao }}</p>
                    <p class="text-sm text-gray-500">
                        Data: 
                        @if ($evento->data_inicio && $evento->data_fim)
                            {{ $evento->data_inicio->format('d/m/Y') }} - {{ $evento->data_fim->format('d/m/Y') }}
                        @else
                            Data não definida
                        @endif
                    </p>

                    <h3 class="text-lg font-medium mt-2 text-lime-700">Atividades Inscritas:</h3>

                    @if ($evento->atividades->isNotEmpty())
                        <div class="flex flex-wrap gap-4 mt-2">
                            @foreach ($evento->atividades as $atividade)
                                <div class="bg-lime-100 p-3 rounded-lg shadow-md w-64 text-gray-600">
                                    <strong class="text-lime-700">{{ $atividade->nome }}</strong> <br>
                                    Data: {{ $atividade->data->format('d/m/Y') }} <br>
                                    Hora: {{ $atividade->hora_inicio->format('H:i') }} - {{ $atividade->hora_fim->format('H:i') }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mt-2">Você não está inscrito em nenhuma atividade deste evento.</p>
                    @endif
                </li>
            </div>
            @endforeach
        </ul>
        @endif
    </div>
@endsection