<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Adicionar Site</h1>

        <form method="POST" action="{{ url('site/adicionar') }}">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Nome do Site</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">URL do Site</label>
                <input type="text" name="url" id="url" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</body>
</html>