@extends('layouts.app')

@section('title', 'Editar Atividade')

@section('content')
    <h1>Editar Atividade</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ocorreu um erro:</strong>
            <ul>
                @foreach ($errors->all() as $error
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário para editar a atividade -->
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
            <label for="data_inicio">Data de Início</label>
            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ old('data_inicio', $atividade->data_inicio->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="data_fim">Data de Fim</label>
            <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{ old('data_fim', optional($atividade->data_fim)->format('Y-m-d')) }}">
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
@endsection