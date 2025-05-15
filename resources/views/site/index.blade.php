<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sites Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Sites Cadastrados</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>URL</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->name }}</td>
                    <td><a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a></td>
                    <td>{{ $site->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>