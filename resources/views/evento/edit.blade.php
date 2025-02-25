@extends('layouts.app')
@section('content')
    <div class="ml-4 mt-4">
        <a href="{{ route('evento.show', $evento->id) }}" class="text-lime-600 hover:underline">
            ← Voltar para página do evento
        </a>
    </div>
    <div class="mx-auto px-2 sm:px-6 lg:px-8 flex m-2 justify-around">
        <div class="px-4 basis-2/4">
            @if ($errors->any())
            <div class="alert alert-danger ">
                <strong>Ocorreu um erro:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li class="text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h1 class="text-2xl font-semibold mb-4 text-lime-700">Editar Evento</h1>
            
            <form action="{{ route('evento.update', $evento->id) }}" method="POST">
                @csrf
                @method('PATCH')
            
                <div class="m-4 w-1/2">
                    <x-input-label for="nome" value="Nome" />
                    <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $evento->nome)" required autofocus autocomplete="nome" />
                </div>
    
                <div class="mx-4 w-2/3">
                    <x-input-label for="descricao" value="Descrição" />
                    <x-textarea-input id="descricao" rows="5" name="descricao" :value="old('descricao', $evento->descricao)" required autofocus autocomplete="descricao"/>
                </div>
    
                <div class="flex space-x-8"> 
                    <div class="m-4">
                        <x-input-label for="data_inicio" value="Data de início:" />
                        <x-date-input id="data_inicio" type="date" name="data_inicio" :value="old('data_inicio', $evento->data_inicio)" required autofocus autocomplete="data_inicio"/>
                        <x-input-error :messages="$errors->get('data_inicio')" class="mt-2" />
                    </div>
                    <div class="m-4">
                        <x-input-label for="data_fim" value="Data de término:" />
                        <x-date-input id="data_fim" type="date" name="data_fim" :value="old('data_fim', $evento->data_fim)" required autofocus autocomplete="data_fim"/>
                        <x-input-error :messages="$errors->get('data_fim')" class="mt-2" />
                    </div>
                </div>
                <div class="m-6">
                    <button type="submit" class="bg-lime-500 text-white text-lg px-4 py-2 rounded-md hover:bg-lime-600">Salvar Alterações</button>
            </form>
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white text-lg px-4 py-2 rounded-md ml-4"  
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-event-modal-{{ $evento->id }}' }))">
                    Excluir Evento
                </div>
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
        
        <div class="basis-1/2 p-6 px-10">
            <!-- Formulário para vincular organizador ao evento -->
            <div class="flex flex-col justify-start m-6 self-start ml-8 w-full">
                <h1 class="text-2xl font-medium text-lime-700">Vincular Organizador</h2>
                <form action="{{ route('registro.vincular', $evento->id) }}" method="POST">
                    @csrf
                    <div class="mx-3 my-4 justify-start items-center space-x-3">
                        <x-input-label for="email" class="mx-3" value="Email do organizador: "/>
                        <x-text-input type="email" id="email" class="w-1/2" name="email" required placeholder="Digite o email do organizador"/>
                    </div>
                    <div class="mx-10 my-6">
                        <button type="submit" class="bg-lime-500 text-white text-md px-2 py-2 rounded-md hover:bg-lime-600">Vincular Organizador</button>
                    </div>
                </form>
            </div>
        </div>                                                                                                                                                                                                                                                                                    
    </div>
@endsection