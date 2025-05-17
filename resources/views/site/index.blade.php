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

        {{-- Mensagens de feedback --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário de Adicionar Novo Site --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Adicionar Novo Site</h5>
                <form action="{{ route('site.adicionar') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="title" class="form-control" placeholder="Título do site (opcional)">
                        </div>
                        <div class="col">
                            <input type="url" name="url" class="form-control" placeholder="https://www.exemplo.com" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabela de Sites --}}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>URL</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->title ?: 'Sem título' }}</td>
                    <td><a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a></td>
                    <td>{{ $site->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('site.editar', $site->id) }}" class="btn btn-primary btn-sm mb-1">Editar</a>
                        <form action="{{ route('site.deletar', $site->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este site?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>

                {{-- Linha adicional para exibir resultado da auditoria --}}
                @if ($site->auditResult)
                <tr>
                    <td colspan="5">
                        <div class="border rounded bg-light p-3">
                            <h5 class="mb-3">🔍 Resultado da Auditoria</h5>
                            <p><strong>Title:</strong> {{ $site->auditResult->title }}</p>
                            <p><strong>Meta Description:</strong> {{ $site->auditResult->meta_description }}</p>
                            <p><strong>OG Title:</strong> {{ $site->auditResult->og_title }}</p>
                            <p><strong>Canonical:</strong> {{ $site->auditResult->canonical }}</p>
                            <p><strong>H1:</strong> {{ $site->auditResult->h1 }}</p>
                            <p><strong>H2:</strong> {{ $site->auditResult->h2 }}</p>
                            <p><strong>H3:</strong> {{ $site->auditResult->h3 }}</p>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>