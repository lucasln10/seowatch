<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalhes do Site - {{ $site->title ?: 'Sem título' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h1>Detalhes do Site</h1>
        <a href="{{ route('site.index') }}" class="btn btn-secondary mb-4">← Voltar para lista</a>

        <div class="card mb-4">
            <div class="card-body">
                <h3>{{ $site->title ?: 'Sem título' }}</h3>
                <p><strong>URL:</strong> <a href="{{ $site->url }}" target="_blank" rel="noopener noreferrer">{{ $site->url }}</a></p>
                <p><strong>Criado em:</strong> {{ $site->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <h2>Resultado da Auditoria SEO</h2>

        @if ($audit)
            <div class="card bg-light p-3">
                <p><strong>Title:</strong> {{ $audit->title ?: 'Não encontrado' }}</p>
                <p><strong>Meta Description:</strong> {{ $audit->meta_description ?: 'Não encontrado' }}</p>
                <p><strong>OG Title:</strong> {{ $audit->og_title ?: 'Não encontrado' }}</p>
                <p><strong>Canonical:</strong> {{ $audit->canonical ?: 'Não encontrado' }}</p>
                <p><strong>H1:</strong> {{ $audit->h1 ?: 'Não encontrado' }}</p>
                <p><strong>H2:</strong> {{ $audit->h2 ?: 'Não encontrado' }}</p>
                <p><strong>H3:</strong> {{ $audit->h3 ?: 'Não encontrado' }}</p>
                <p><small class="text-muted">Última auditoria em {{ $audit->created_at->format('d/m/Y H:i') }}</small></p>
            </div>
        @else
            <div class="alert alert-info">
                Ainda não há resultados de auditoria para este site.
            </div>
        @endif
    </div>
</body>
</html>