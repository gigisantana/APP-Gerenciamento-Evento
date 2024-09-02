<a href="{{route('evento.create')}}">Novo</a>
<hr>
<table>
    <theader>
        <th>ID</th>
        <th>NOME</th>
        <th>DESCRIÇÃO</th>
        <th>AÇÕES</th>
    </theader>
    <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->nome}}</td>
                    <td>{{$item->descricao}}</td>
                    <td>
                        <a href="{{route('evento.show', $item->id)}}" >Info</a>
                        <a href="{{route('evento.edit', $item->id)}}" >Alterar</a>
                        <a href="{{route('evento.destroy', $item->id)}}" >Remover</a>
                    </td>
                </tr>        
            @endforeach

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('evento.create') }}" class="btn btn-primary btn-block mb-3">Novo Evento</a>
            
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
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->descricao }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('evento.show', $item->id) }}" class="btn btn-info btn-sm">Info</a>
                                            <a href="{{ route('evento.edit', $item->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <a href="{{ route('evento.destroy', $item->id) }}" class="btn btn-danger btn-sm">Excluir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </tbody>
</table>