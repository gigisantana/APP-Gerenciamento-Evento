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
    </tbody>
</table>