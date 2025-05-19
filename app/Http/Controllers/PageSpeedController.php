<?php

namespace App\Http\Controllers;

use App\Services\PageSpeedService;
use Illuminate\Http\Request;
use App\Models\AuditResult;
use App\Models\AuditMetric;

class PageSpeedController extends Controller
{
    public function pageSpeed(PageSpeedService $pageSpeed, $auditResultId)
    {
        $avisos = [];
        $auditResult = AuditResult::find($auditResultId);
        $url = $auditResult->site->url;

        $dados = $pageSpeed->runAnalysis($url);

        $score = $dados['lighthouseResult']['categories']['performance']['score'] ?? null;
        $fcp = $dados['lighthouseResult']['audits']['first-contentful-paint']['displayValue'] ?? null;
        $speedIndex = $dados['lighthouseResult']['audits']['speed-index']['displayValue'] ?? null;
        $lcp = $dados['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null;
        $cls = $dados['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null;

        //Verificações da performance
        if ($score === null) {
            $avisos[] = 'Erro ao obter o score de performance.';
        } elseif ($score < 0.5) {
            $avisos[] = 'O score de performance é baixo. Considere otimizar seu site.';
        } elseif ($score < 0.9) {
            $avisos[] = 'O score de performance é médio. Algumas melhorias podem ser feitas.';
        } else {
            $avisos[] = 'O score de performance é bom.';
        }

        if ($this->valorEmMs($fcp) === null) {
            $avisos[] = 'Erro ao obter o First Contentful Paint.';
        } elseif ($this->valorEmMs($fcp) > 2000) {
            $avisos[] = 'O First Contentful Paint é alto. Considere otimizar seu site.';
        } else {
            $avisos[] = 'O First Contentful Paint está dentro do esperado.';
        }

        if ($this->valorEmMs($speedIndex) === null) {
            $avisos[] = 'Erro ao obter o Speed Index.';
        } elseif ($this->valorEmMs($speedIndex) > 3000) {
            $avisos[] = 'O Speed Index é alto. Considere otimizar seu site.';
        } else {
            $avisos[] = 'O Speed Index está dentro do esperado.';
        }

        if ($lcp === null) {
            $avisos[] = 'Erro ao obter o Largest Contentful Paint.';
        } elseif ($lcp > 2500) {
            $avisos[] = 'O Largest Contentful Paint é alto. Considere otimizar seu site.';
        } else {
            $avisos[] = 'O Largest Contentful Paint está dentro do esperado.';
        }

        if ($cls === null) {
            $avisos[] = 'Erro ao obter o Cumulative Layout Shift.';
        } elseif ($cls > 0.1) {
            $avisos[] = 'O Cumulative Layout Shift é alto. Considere otimizar seu site.';
        } else {
            $avisos[] = 'O Cumulative Layout Shift está dentro do esperado.';
        }

        if (empty($avisos)) {
            $avisos[] = 'Nenhum aviso encontrado.';
        }

        $avisos[] = 'Análise concluída com sucesso.';
        $avisos[] = 'URL: ' . $url;
        $avisos[] = 'Estratégia: desktop';
        $avisos[] = 'Data: ' . date('Y-m-d H:i:s');

        $scoreInt = is_null($score) ? null : floatval($score * 100);
        $fcpMs = $this->valorEmMs($fcp);
        $speedIndexMs = $this->valorEmMs($speedIndex);

        // Salvar no banco
        AuditMetric::create([
            'audit_result_id' => $auditResult->id,
            'score' => $scoreInt,
            'fcp' => $fcpMs,
            'speed_index' => $speedIndexMs,
            'lcp' => $lcp,
            'cls' => $cls,
            'feedback' => $avisos,
        ]);

        return response()->json([
            'score' => $score,
            'first_contentful_paint' => $fcp,
            'speed_index' => $speedIndex,
            'largest_contentful_paint' => $lcp,
            'cumulative_layout_shift' => $cls,
            'feedback' => $avisos,
        ]);
    }
    private function valorEmMs($valorComS)
    {
        if (empty($valorComS) || !str_contains($valorComS, 's')) {
            return null;
        }
        return floatval(str_replace('s', '', $valorComS)) * 1000;
    }

    public function showReport($auditResultId)
    {
        $auditResult = AuditResult::with('auditMetrics', 'site')->findOrFail($auditResultId);
        $metrics = $auditResult->auditMetrics->last(); // pega a métrica mais recente
        return view('audit.show', compact('auditResult',    'metrics'));
    }
}
