@extends('layouts.app')
@section('content')
<div class="m-6 flex-wrap">
    @if ($errors->any())
    <div class="alert alert-danger bg-red-300 justify-center text-center text-red-900 px-4 py-1.5 my-4 mr-2 rounded-md">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <a href="{{ route('home') }}" class="text-lime-600 hover:underline">
        ← Voltar para página de eventos
    </a>
    <div>
        <form action="{{route('evento.store')}}" method="POST">
            @csrf
            <div class="m-6">
                <h1 class="text-2xl font-bold text-lime-700 mb-4 ">Criar Novo Evento</h1>
                
                <div class="m-4">
                    <x-input-label for="nome" :value="__('Nome do evento:')" />
                    <x-text-input id="nome" type="text" name="nome" />
                    <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                </div>
                <div class="flex space-x-8"> 
                    <div class="m-4">
                        <x-input-label for="data_inicio" :value="__('Data de início:')" />
                        <x-date-input id="data_inicio" type="date" name="data_inicio" />
                        <x-input-error :messages="$errors->get('data_inicio')" class="mt-2" />
                    </div>
                    <div class="m-4">
                        <x-input-label for="data_fim" :value="__('Data de término:')" />
                        <x-date-input id="data_fim" type="date" name="data_fim" />
                        <x-input-error :messages="$errors->get('data_fim')" class="mt-2" />
                    </div>
                </div>
                <div class="m-4 w-1/2 ">
                    <x-input-label for="descricao" :value="__('Descrição do evento:')" />
                    <x-textarea-input id="descricao" rows="5" class="mt-2 placeholder:text-sm placeholder:text-gray-400" type="text" name="descricao" placeholder="Qual é o objetivo do seu evento?" :value="old('descricao')" required />
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>
                <div class="flex justify-center items-center">
                    <x-primary-button class="mt-4 ml-6 justify-center">
                        {{ __('Criar Evento') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection