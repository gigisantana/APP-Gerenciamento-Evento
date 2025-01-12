@extends('layouts.app')

@section('title', 'Editar Atividade')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold mb-4 text-lime-700">Editar Atividade</h1>

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
        <form action="{{ route('atividades.update', $atividade->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nome">Nome da Atividade</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $atividade->nome) }}" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao">{{ old('descricao', $atividade->descricao) }}</textarea>
            </div>

            <div class="form-group">
                <label for="data_inicio">Data</label>
                <input type="date" class="form-control" id="data_inicio" name="data" value="{{ old('data', $atividade->data->format('Y-m-d')) }}" required>
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

            <div class="form-group">
                <label for="evento_id">Evento Relacionado</label>
                <select class="form-control" id="evento_id" name="evento_id" required>
                    @foreach($eventos as $evento)
                        <option value="{{ $evento->id }}" {{ $atividade->evento_id == $evento->id ? 'selected' : '' }}>
                            {{ $evento->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="{{ route('atividades.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <div class="">
        <h2 class="mt-4">Vincular Organizador</h2>
        <form action="{{ route('registro.vincular', $atividade->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email do Organizador</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="Digite o email do organizador">
            </div>

            <button type="submit" class="btn btn-success mt-2">Vincular Organizador</button>
        </form>
    </div>
</div>
@endsection