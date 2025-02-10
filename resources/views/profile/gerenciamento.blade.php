@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold mb-4 text-lime-700">Gerenciar Eventos</h1>
    <form method="GET" action="{{ route('profile.gerenciamento') }}">
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-4">
        {{-- Coluna Organizador --}}
        <div>
            <h2 class="text-xl font-bold mb-4 text-lime-700">Eventos como Organizador</h2>
            
            @if ($vinculosOrganizador->isEmpty())
                <p class="text-gray-600">Você ainda não é organizador de nenhum evento.</p>
            @else
                <ul class="space-y-4">
                    @forelse ($vinculosOrganizador->sortBy('data_inicio') as $evento)
                        <div onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'" 
                             class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                            <li class="p-4 border rounded-lg">
                                <div class="flex gap-5">
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
                        @foreach ($vinculosCoordenador as $evento)
                            <div onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'" 
                                 class="cursor-pointer bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                                <li class="p-4 border rounded-lg">
                                    <div class="flex gap-5">
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
                                            Data não disponível
                                        @endif
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