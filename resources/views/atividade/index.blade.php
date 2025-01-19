@extends('layouts.app')

@section('title', 'Lista de Atividades')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>Atividades</h1>
        <a href="{{ route('atividade.create', $evento->id) }}" class="btn btn-primary">Criar Nova Atividade</a>
    </div>

    @if($atividade)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Evento</th>
                    <th>Data de Início</th>
                    <th>Data de Fim</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($atividades as $atividade)
                    <tr>
                        <td>{{ $atividade->nome }}</td>
                        <td>{{ $atividade->evento->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($atividade->data_inicio)->format('d/m/Y') }}</td>
                        <td>{{ $atividade->data_fim ? \Carbon\Carbon::parse($atividade->data_fim)->format('d/m/Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('atividade.show', $atividade->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('atividade.edit', $atividade->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('atividades.destroy', $atividade->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Tem certeza que deseja deletar esta atividade?')">
                                    Deletar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $atividades->links() }}
    @else
        <p>Nenhuma atividade encontrada.</p>
    @endif
@endsection