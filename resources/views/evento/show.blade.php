@extends('layouts.divider')
@section('content')
    <div class="container mx-auto px-4">
        <!-- Cabeçalho do Evento -->
        <div class="flex justify-between">
            @if($userRole === 1) {{-- Coordenador --}}
                <div class="">
                    <a href="{{ route('evento.edit', ['id' => $evento->id]) }}" class="justify-center bg-lime-500 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-lime-600">
                        Editar Evento
                    </a>
                    <button class="bg-red-500 hover:bg-red-600 justify-center text-center text-white px-4 py-1.5 my-4 mr-2 rounded-md" 
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-event-modal-{{ $evento->id }}' }))">
                        Excluir Evento
                    </button>
                        <x-modal name="delete-event-modal-{{ $evento->id }}" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Tem certeza de que deseja excluir este evento?
                                </h2>
                    
                                <p class="mt-1 text-sm text-gray-600">
                                    Esta ação é irreversível e resultará na exclusão permanente do evento.
                                </p>
                    
                                <div class="mt-6 flex justify-end space-x-4">
                                    <x-secondary-button x-on:click="$dispatch('close-modal', 'delete-event-modal-{{ $evento->id }}')">
                                        Cancelar
                                    </x-secondary-button>
                                    <form method="POST" action="{{ route('evento.destroy', $evento->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button>
                                            Confirmar Exclusão
                                        </x-danger-button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>              
                </div>
            @endif    
        </div>

        <div class="bg-lime-100 text-lime-700 text-center p-6 rounded-md shadow-md mb-2">
            <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
            <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
            <p class="mt-4">
                <strong>Data:</strong> {{ $evento->data_inicio->format('d/m/Y') }} - {{ $evento->data_fim->format('d/m/Y') }}
            </p>
        </div>

        <div>
            @if(($userRole === 1 || $userRole === 2)) {{-- Coordenador ou Organizador --}}
                <div class="my-4">
                    <a href="{{ route('atividade.create', ['id' => $evento->id]) }}" class=" justify-center bg-lime-500 text-white text-center px-4 py-2 my-4 mr-2 rounded-md hover:bg-lime-600">
                        Criar Atividade
                    </a>
                </div>
            @endif
        </div>

        <!-- Atividades do Evento -->
        <div>
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
                                <strong>Horário:</strong> {{ $atividade->hora_inicio->format('H:i') }} - {{ $atividade->hora_fim->format('H:i') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Local:</strong>
                                @if($atividade->local)
                                    {{ $atividade->local->espaco }} ({{ $atividade->local->bloco }})
                                @else
                                    Não definido
                                @endif
                            </p>
                            <div class="flex justify-center items-center">
                            @auth
                                @php
                                    $registro = $atividade->registro->firstWhere('user_id', auth()->id());
                                    $userRole = $registro->role->id ?? null;                                    
                                @endphp
                                

                                @if ($registro && $userRole === 3)
                                    <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 mt-4 rounded-md">
                                        Inscrito!
                                    </button>
                                @elseif(!$registro)
                                    <form action="{{ route('registro.inscrever', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" method="POST" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                        <input type="hidden" name="atividade_id" value="{{ $atividade->id }}">
                                        <input type="hidden" name="role_id" value="3"> <!-- Inscrito -->
                                        <button type="submit" class="bg-lime-500 text-white px-4 py-2 rounded-md hover:bg-lime-600">
                                            Inscrever-se
                                        </button>
                                    </form>
                                @elseif($registro && ($userRole === 1 || $userRole === 2))
                                    <div class="flex ">
                                        @if(($userRole === 1 || $userRole === 2)) {{-- Coordenador ou Organizador --}}
                                            <div class="my-3.5">
                                                <a href="{{ route('atividade.edit', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}" class="justify-center bg-orange-500 hover:bg-orange-600 text-white text-center px-4 py-2 my-2 mr-2 rounded-md ">
                                                    Editar
                                                </a>
                                            </div>
                                            <div class="mt-2">
                                                <button type="button" class="justify-center bg-red-500 hover:bg-red-600 text-white text-center px-3.5 py-1.5 rounded-md " onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-event-modal-{{ $atividade->id }}' }))">
                                                    Excluir
                                                </button>
                                            </div>
                                            <x-modal name="delete-event-modal-{{ $atividade->id }}" maxWidth="md">
                                                <div class="p-6">
                                                    <h2 class="text-lg font-medium text-gray-900">
                                                        Tem certeza de que deseja excluir esta atividade?
                                                    </h2>
                                        
                                                    <p class="mt-1 text-sm text-gray-600">
                                                        Esta ação é irreversível e resultará na exclusão permanente da atividade.
                                                    </p>
                                        
                                                    <div class="mt-6 flex justify-end space-x-4">
                                                        <x-secondary-button x-on:click="$dispatch('close-modal', 'delete-event-modal-{{ $atividade->id }}')">
                                                            Cancelar
                                                        </x-secondary-button>
                                                        <form method="POST" action="{{ route('atividade.destroy', ['id' => $evento->id, 'atividade_id' => $atividade->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-danger-button>
                                                                Confirmar Exclusão
                                                            </x-danger-button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </x-modal>
                                        @endif
                                    </div>
                                
                                @endif
                                @endauth
                                    @guest
                                        <a href="{{ route('login') }}" class=" justify-center bg-lime-500 text-white text-center px-4 py-2 mt-4 rounded-md hover:bg-lime-600">
                                            Inscrever-se!
                                        </a>
                                    @endguest
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
    </div>
@endsection