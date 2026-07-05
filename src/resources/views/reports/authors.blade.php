<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Autores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .bloco-autor {
            margin-bottom: 40px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        h2 {
            background-color: #e2e8f0;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px 0;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .no-books {
            font-style: italic;
            color: #64748b;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Relatório de Autores e Livros</h1>

    @foreach($autores as $autor)
        <div class="bloco-autor">
            <h2>Autor: {{ $autor['nome'] }}</h2>
            
            @if(count($autor['livros']) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Livro</th>
                            <th>Editora</th>
                            <th>Ano</th>
                            <th>Assuntos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($autor['livros'] as $livro)
                            <tr>
                                <td>{{ $livro['titulo'] }}</td>
                                <td>{{ $livro['editora'] }}</td>
                                <td>{{ $livro['ano_publicacao'] }}</td>
                                <td>{{ $livro['assuntos_concatenados'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-books">Nenhum livro registrado para este autor.</div>
            @endif
        </div>
    @endforeach
</body>
</html>
