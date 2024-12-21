<a href="{{ route('evento.create') }}" class="btn btn-primary btn-block mb-3">Novo Evento</a>
<hr>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>DESCRIÇÃO</th>
            <th class="text-center">AÇÕES</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($evento as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->nome }}</td>
                <td>{{ $item->descricao }}</td>
                <td class="text-center">
                    <a href="{{ route('evento.show', $item->id) }}" class="btn btn-info btn-sm">Info</a>
                    <a href="{{ route('evento.edit', $item->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('evento.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
