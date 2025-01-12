@extends('layouts.app')

@section('title', 'Editar Evento')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-semibold mb-4 text-lime-700">Editar Evento</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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

            <!-- Formulário para editar a atividade -->
            <div>
                <form action="{{ route('evento.update', $evento->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nome">Nome do Evento</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $evento->nome) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao">{{ old('descricao', $evento->descricao) }}</textarea>
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

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="{{ route('evento.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

            <!-- Formulário para vincular organizador ao evento -->
            <div class="">
                <h2 class="mt-4">Adicionar Organizador</h2>
                <form action="{{ route('registro.vincular', $evento->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email do Organizador</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Digite o email do organizador">
                    </div>

                    <button type="submit" class="btn btn-success mt-2">Vincular</button>
                </form>
            </div>
        </div>
    </div>
@endsection