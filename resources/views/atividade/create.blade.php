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
            
                <h1 class="text-2xl font-bold text-lime-700 mb-4">Criar Nova Atividade</h1>

                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                <div class="m-4">
                    <x-input-label for="nome" value="Nome da atividade:" />
                    <x-text-input id="nome" type="text" name="nome" />
                    <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                </div>
                <div class="m-4">
                    <x-input-label for="data" value="Data:" />
                    <x-date-input id="data" type="date" name="data" />
                    <x-input-error :messages="$errors->get('data')" class="mt-2" />
                </div>
                <div class="flex space-x-8"> 
                    <div class="m-4">
                        <x-input-label for="hora_inicio" value="Horário de início:" />
                        <input id="hora_inicio" type="time" name="hora_inicio" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm" />
                        <x-input-error :messages="$errors->get('hora_inicio')" class="mt-2" />
                    </div>
                    <div class="m-4">
                        <x-input-label for="hora_fim" value="Horário de término:" />
                        <input id="hora_fim" type="time" name="hora_fim" class="border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm"/>
                        <x-input-error :messages="$errors->get('hora_fim')" class="mt-2" />
                    </div>
                </div>
                <div class="mx-4 w-1/2">
                    <x-input-label for="descricao" value="Descrição da atividade:" />
                    <x-textarea-input id="descricao" rows="5" class="mt-2 placeholder:text-sm placeholder:text-gray-400" type="text" name="descricao" placeholder="Como será a atividade?" :value="old('descricao')" required />
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>

                <div class="m-4 flex justify-center items-center">
                    <button type="submit" class="bg-lime-500 text-white text-lg px-4 py-2 rounded-md hover:bg-lime-600">Criar Atividade</button>
                </div>
            </form>
        </div>
        <div class="flex flex-col px-4 basis-2/4 justify-top">
            <div class="bg-lime-100 text-lime-700 text-center p-4 rounded-md shadow-md w-4/5">
                <h1 class="text-3xl font-bold">{{ $evento->nome }}</h1>
                <p class="mt-2 text-lg">{{ $evento->descricao }}</p>
                <p class="mt-4">
                    <strong>Início:</strong> {{ $evento->data_inicio->format('d/m/Y') }}
                      
                    <strong>Fim:</strong> {{ $evento->data_fim->format('d/m/Y') }}                  
                </p>               
            </div>

            <div class="m-6 self-start ml-8">
                <h1 class="text-2xl font-medium text-lime-700">Vincular Organizador</h2>
                <form action="{{ route('registro.vincular', $evento->id) }}" method="POST">
                    @csrf
    
                    <div class="form-group">
                        <label for="email">Email do Organizador</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Digite o email do organizador"/>
                    </div>
    
                    <button type="submit" class="btn btn-success mt-2">Vincular Organizador</button>
                </form>
            </div>
        </div>
    </div>
@endsection