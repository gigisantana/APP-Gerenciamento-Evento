<form action="{{route('evento.store')}}" method="POST">
    @csrf
    <label>Nome do Evento</label>
    <br>
    <input type="text" name="nome" />
    <br>
    <label>Nome do Evento</label>
    <br>
    <textarea name="descricao" rows="5" cols="21"></textarea>
    <br>
    <input type="submit" value="Salvar" />
</form>