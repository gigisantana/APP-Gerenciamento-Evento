<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Inscrições</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid rgb(163, 230, 53);
        }

        th {
            background-color: rgb(163, 230, 53);
        }

        h1 {
            text-align: center;
            color: rgb(77, 124, 15);
        } 
        h2 {
            color: rgb(163, 230, 53);
        }

        .header {
            text-align: left;
        }

        .event-info {
            margin-top: 10px;
        }

        .event-info p {
            margin: 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Inscrições</h1>
        <h2>{{ $atividade->nome }} - {{ $atividade->evento->nome }}</h2>
        <p class="event-info">
            <strong>Evento:</strong> {{ $atividade->evento->nome }} <br>
            <strong>Data do Evento:</strong> {{ $atividade->evento->data_inicio->format('d/m/Y') }} a {{ $atividade->evento->data_fim->format('d/m/Y') }} <br>
            <strong>Atividade:</strong> {{ $atividade->nome }} <br>
            <strong>Data da Atividade:</strong> {{ $atividade->data->format('d/m/Y') }} <br>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sobrenome</th>
                <th>Email</th>
                <th>Data da Inscrição</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscritos as $inscrito)
                <tr>
                    <td>{{ $inscrito->user->nome }}</td>
                    <td>{{ $inscrito->user->sobrenome }}</td>
                    <td>{{ $inscrito->user->email }}</td>
                    <td>{{ $inscrito->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
