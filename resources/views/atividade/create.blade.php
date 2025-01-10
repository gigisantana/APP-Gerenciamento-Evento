@extends('layouts.app')

@section('title', 'Criar Atividade')

@section('content')
    <h1>Criar Nova Atividade</h1>

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

    <form action="{{ route('atividade.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Atividade</label>
            <input type="text" name="nome" class="form-control" id="nome" value="{{ old('nome') }}" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" id="descricao">{{ old('descricao') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" class="form-control" id="data_inicio" value="{{ old('data_inicio') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" class="form-control" id="data_fim" value="{{ old('data_fim') }}">
        </div>

        <div class="mb-3">
            <label for="evento_id" class="form-label">Evento</label>
            <select name="evento_id" id="evento_id" class="form-control" required>
                <option value="">-- Selecione um Evento --</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}" {{ old('evento_id') == $evento->id ? 'selected' : '' }}>
                        {{ $evento->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('atividade.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection