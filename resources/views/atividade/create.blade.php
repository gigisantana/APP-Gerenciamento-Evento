@extends('layouts.app')
@section('content')
<div class="mx-auto px-2 sm:px-6 lg:px-8 py-4 flex m-2 justify-around">
    <div class="px-4 basis-2/4">
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ocorreu um erro:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('atividade.store', ['id' => $evento->id]) }}" method="POST">
            @csrf
        
            <h1 class="text-2xl font-bold text-lime-700 mb-4 ">Criar Nova Atividade</h1>

            <input type="hidden" name="evento_id" value="{{ id }}">
            <div class="m-4">
                <x-input-label for="nome" :value="__('Nome da atividade:')" />
                <x-text-input id="nome" type="text" name="nome" />
                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
            </div>
            <div class="m-4">
                <x-input-label for="data" :value="__('Data:')" />
                <x-date-input id="data" type="date" name="data" />
                <x-input-error :messages="$errors->get('data')" class="mt-2" />
            </div>
            <div class="flex space-x-8"> 
                <div class="m-4">
                    <x-input-label for="hora_inicio" :value="__('Horário de início:')" />
                    <x-date-input id="hora_inicio" type="time" name="hora_inicio" />
                    <x-input-error :messages="$errors->get('hora_inicio')" class="mt-2" />
                </div>
                <div class="m-4">
                    <x-input-label for="hora_fim" :value="__('Horário de término:')" />
                    <x-date-input id="hora_fim" type="time" name="hora_fim" />
                    <x-input-error :messages="$errors->get('hora_fim')" class="mt-2" />
                </div>
            </div>
            <div class="m-4 w-1/2 ">
                <x-input-label for="descricao" :value="__('Descrição da atividade:')" />
                <x-textarea-input id="descricao" rows="5" class="mt-2 placeholder:text-sm placeholder:text-gray-400" type="text" name="descricao" placeholder="Como será a atividade?" :value="old('descricao')" required />
                <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
            </div>

            <div class="m-4 flex justify-center items-center">
                <x-primary-button class="mt-4 ml-6 justify-center">
                    {{ __('Criar Atividade') }}
                </x-primary-button>
            </div>
        </form>
    </div>
    <div class="flex flex-col px-4 basis-2/4 justify-top items-center">
        <div class=" bg-lime-100 text-lime-700 text-center p-4 rounded-md shadow-md w-4/5">
            <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
            <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
            <p class="mt-4">
                <strong>Início:</strong> {{ $evento->data_inicio }}
                <br>  
                <strong>Fim:</strong> {{ $evento->data_fim }}                  
            </p>
        </div>
    </div>
</div>
@endsection