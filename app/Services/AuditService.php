<?php

namespace App\Services;

use App\Models\AuditResult;
use App\Models\AuditMetric;
use App\Services\PageSpeedService;
use Illuminate\Support\Facades\Http;

class AuditService
{
    public function __construct(PageSpeedService $pageSpeed, SeoCrawler $seoCrawler)
    {
        $this->pageSpeed = $pageSpeed;
        $this->seoCrawler = $seoCrawler;
    }

    public function runAudit(AuditResult $auditResult): array
    {
        if (!$auditResult || !$auditResult->id) {
            throw new \InvalidArgumentException("AuditResult inválido ou não persistido.");
        }
        try {
            $avisos = [];
            $url = $auditResult->site->url;
            $auditResult->update(['status' => 'processando']);

            // 🕷 CRAWLER SEO ON-PAGE
            $seoData = $this->seoCrawler->extract($url);
            \Log::info(print_r($seoData, true));
            if (!empty($seoData)) {
                $auditResult->update($seoData);
            }

            $dados = $this->pageSpeed->runAnalysis($url);

            $score = data_get($dados, 'lighthouseResult.categories.performance.score');
            $fcp = $dados['lighthouseResult']['audits']['first-contentful-paint']['displayValue'] ?? null;
            $speedIndex = $dados['lighthouseResult']['audits']['speed-index']['displayValue'] ?? null;
            $lcp = $dados['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'] ?? null;
            $cls = $dados['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'] ?? null;

            if ($score === null) {
                $avisos[] = 'Erro ao obter o score de performance.';
            } elseif ($score < 0.5) {
                $avisos[] = 'O score de performance é baixo. Considere otimizar seu site.';
            } elseif ($score < 0.9) {
                $avisos[] = 'O score de performance é médio. Algumas melhorias podem ser feitas.';
            } else {
                $avisos[] = 'O score de performance é bom.';
            }

            $avisos[] = 'Análise concluída com sucesso.';
            $avisos[] = 'URL: ' . $url;
            $avisos[] = 'Estratégia: desktop';
            $avisos[] = 'Data: ' . date('Y-m-d H:i:s');

            $scoreInt = is_null($score) ? null : floatval($score * 100);
            $fcpMs = $this->valorEmMs($fcp);
            $speedIndexMs = $this->valorEmMs($speedIndex);

            AuditMetric::create([
                'audit_result_id' => $auditResult->id,
                'score' => $score !== null ? floatval($score * 100) : null,
                'fcp' => $fcpMs,
                'speed_index' => $speedIndexMs,
                'lcp' => $lcp,
                'cls' => $cls,
                'feedback' => $avisos,
            ]);
            \Log::info('SEO DATA EXTRAÍDO:', $seoData);
            $auditResult->update([
                'status' => 'concluído',
                'feedback' => $avisos,
            ]);

            return [
                'score' => $score,
                'first_contentful_paint' => $fcp,
                'speed_index' => $speedIndex,
                'largest_contentful_paint' => $lcp,
                'cumulative_layout_shift' => $cls,
                'feedback' => $avisos,
            ];
        } catch (\Exception $e) {
            \Log::error("Erro ao executar auditoria: {$e->getMessage()}", ['auditResultId' => $auditResult->id ?? null]);
            report($e);
            $auditResult->update([
                'status' => 'error',
                'feedback' => ['Erro ao executar auditoria: ' . $e->getMessage()],
            ]);

            return ['feedback' => ['Erro ao executar auditoria: ' . $e->getMessage()]];
        }
    }

    private function valorEmMs($valorComS)
    {
        if (empty($valorComS) || !str_contains($valorComS, 's')) {
            return null;
        }
        return floatval(str_replace('s', '', $valorComS)) * 1000;
    }

    private function crawlSeoData(string $url): array
    {
        libxml_use_internal_errors(true);
        $response = Http::get($url);
        if ($response->failed()) {
            return [];
        }

        $html = $response->body();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $getAttr = fn($query, $attr) =>
            ($node = $xpath->query($query)->item(0)) ? trim($node->getAttribute($attr)) : null;

        $getTextFromTags = fn($tag) => collect(iterator_to_array($dom->getElementsByTagName($tag)))
            ->map(fn($node) => trim($node->textContent))
            ->filter()
            ->implode(' | ');

        return [
            'title' => ($t = $dom->getElementsByTagName('title')->item(0)) ? trim($t->textContent) : null,
            'meta_description' => $getAttr('//meta[@name="description"]', 'content'),
            'og_title' => $getAttr('//meta[@property="og:title"]', 'content'),
            'canonical' => $getAttr('//link[@rel="canonical"]', 'href'),
            'h1' => $getTextFromTags('h1'),
            'h2' => $getTextFromTags('h2'),
            'h3' => $getTextFromTags('h3'),
        ];
    }
}