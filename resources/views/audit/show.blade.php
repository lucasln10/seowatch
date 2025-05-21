@extends('layouts.app')

@section('content')

<div class="container">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('audit.run', $auditResult ? $auditResult->site->id : request()->route('id')) }}" method="POST" class="mb-4">
        @csrf
        <button type="submit" class="btn btn-primary">Rodar Auditoria</button>
    </form>

    @if($auditResult)
        <h1 class="mb-4">Relatórios de Auditoria - {{ $auditResult->site->url }}</h1>

        @if($auditResult->auditMetrics->isEmpty())
            <div class="alert alert-warning">Nenhuma métrica encontrada para este relatório.</div>
        @else
            @foreach ($auditResult->auditMetrics as $metric)
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Relatório #{{ $loop->iteration }}</strong> - 
                        <small>{{ $metric->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Score de Performance (Pontuação de Desempenho)</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar {{ getProgressBarClass($metric->score / 100) }}"
                                 role="progressbar"
                                 style="width: {{ $metric->score }}%;"
                                 aria-valuenow="{{ $metric->score }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ $metric->score }}%
                            </div>
                        </div>

                        <p><strong>FCP (First Contentful Paint):</strong> 
                            <span class="badge {{ getBadgeClassFcp($metric->fcp) }}">
                                {{ formatMs($metric->fcp) }}
                            </span>
                        </p>

                        <p><strong>Speed Index (Índice de Velocidade):</strong> 
                            <span class="badge {{ getBadgeClassSpeedIndex($metric->speed_index) }}">
                                {{ formatMs($metric->speed_index) }}
                            </span>
                        </p>

                        <p><strong>LCP (Largest Contentful Paint):</strong> 
                            <span class="badge {{ getBadgeClassLcp($metric->lcp) }}">
                                {{ formatMs($metric->lcp) }}
                            </span>
                        </p>

                        <p><strong>CLS (Cumulative Layout Shift):</strong> 
                            <span class="badge {{ getBadgeClassCls($metric->cls) }}">
                                {{ number_format($metric->cls, 2) }}
                            </span>
                        </p>

                        <h6 class="mt-3">Feedback</h6>
                        @if(!empty($metric->feedback) && is_array($metric->feedback) && count($metric->feedback) > 0)
                            <ul>
                                @foreach ($metric->feedback as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p><em>Mensagem não disponível - Desconhecida</em></p>
                        @endif

                    </div>
                </div>
            @endforeach
        @endif

    @else
        <div class="alert alert-info">
            Nenhum relatório de auditoria encontrado para este site.
        </div>
    @endif

</div>

@endsection