@extends('layouts.app')
@section('content')
<form action="{{route('evento.store')}}" method="POST">
    @csrf
    <div class="m-6">
        <label>Nome do Evento</label>
        <br>
        <input type="text" name="nome" />
        <br>
        <label>Data do Evento</label>
        <div>
            <x-input-label for="data-inicio" :value="__('Data de início:')" />
            <input type="date" name="data_inicio" />
            <br>
            <p>Data final:</p>
            <input type="date" name="data_fim" />
            <br>
        </div>
        <label>Descrição</label>
        <br>
        <textarea name="descricao" rows="5" cols="21"></textarea>
        <br>
        <input type="submit" value="Salvar" />
    </div>
</form>
@endsection