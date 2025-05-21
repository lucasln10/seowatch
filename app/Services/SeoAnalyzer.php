<?php

namespace App\Services;

use App\Models\AuditResult;
use App\Services\SeoCrawler;

class SeoAnalyzer
{
    public function analyze(SeoCrawler $seoData, AuditResult $auditResult)
    {
        $mensagens = [];
        $totais = [
            'erro' => 0,
            'aviso' => 0,
            'ok' => 0
        ];

        // Título
        $title = $seoData->title;
        if (empty($title)) {
            $mensagens[] = $this->registrar('title', 'Título ausente.', 'erro', $totais);
        } else {
            $mensagens[] = $this->avaliarTamanho('title', $title, 50, 60, $totais);
        }

        // Meta description
        $description = $seoData->metaDescription;
        if (empty($description)) {
            $mensagens[] = $this->registrar('description', 'Meta description ausente.', 'erro', $totais);
        } else {
            $mensagens[] = $this->avaliarTamanho('description', $description, 120, 160, $totais);
        }

        // Headings: H1
        $h1 = $seoData->h1Texts;
        if (empty($h1)) {
            $mensagens[] = $this->registrar('h1', 'H1 ausente.', 'erro', $totais);
        } elseif (count($h1) > 1) {
            $mensagens[] = $this->registrar('h1', 'Múltiplos H1 encontrados (' . count($h1) . ').', 'aviso', $totais);
        } else {
            $mensagens[] = $this->registrar('h1', 'H1 presente corretamente.', 'ok', $totais);
        }

        // Hierarquia de headings
        $h2 = $seoData->h2Texts;
        $h3 = $seoData->h3Texts;

        if (!empty($h1)) {
            if (!empty($h2)) {
                if (!empty($h3)) {
                    $mensagens[] = $this->registrar('headings', 'Hierarquia de headings correta: H1 → H2 → H3.', 'ok', $totais);
                } else {
                    $mensagens[] = $this->registrar('headings', 'H2 presente, mas H3 ausente.', 'aviso', $totais);
                }
            } elseif (!empty($h3)) {
                $mensagens[] = $this->registrar('headings', 'H3 presente sem H2 — possível quebra de hierarquia.', 'aviso', $totais);
            } else {
                $mensagens[] = $this->registrar('headings', 'H1 presente, mas H2 e H3 ausentes.', 'aviso', $totais);
            }
        }

        // Open Graph Title
        $ogTitle = $seoData->ogTitle;
        if (empty($ogTitle)) {
            $mensagens[] = $this->registrar('og:title', 'Open Graph Title ausente.', 'erro', $totais);
        } else {
            $mensagens[] = $this->avaliarTamanho('og:title', $ogTitle, 60, 60, $totais); // pode ajustar limites se quiser
        }

        // Canonical
        $canonical = $seoData->canonical;
        if (empty($canonical) || !filter_var($canonical, FILTER_VALIDATE_URL)) {
            $mensagens[] = $this->registrar('canonical', 'Canonical ausente ou inválido.', 'erro', $totais);
        } else {
            $mensagens[] = $this->registrar('canonical', 'Canonical presente corretamente.', 'ok', $totais);
        }

        // Salvando
        $auditResult->feedback = $mensagens;
        $auditResult->erro_count = $totais['erro'];
        $auditResult->aviso_count = $totais['aviso'];
        $auditResult->ok_count = $totais['ok'];
        $auditResult->save();

        return $mensagens;
    }

    private function avaliarTamanho(string $campo, string $valor, int $min, int $max, array &$totais): array
    {
        $len = strlen($valor);
        if ($len < $min) {
            return $this->registrar($campo, ucfirst($campo) . " muito curto ({$len} caracteres).", 'aviso', $totais);
        } elseif ($len > $max) {
            return $this->registrar($campo, ucfirst($campo) . " muito longo ({$len} caracteres).", 'aviso', $totais);
        }
        return $this->registrar($campo, ucfirst($campo) . " com tamanho ideal ({$len} caracteres).", 'ok', $totais);
    }

    private function registrar(string $tipo, string $mensagem, string $severidade, array &$totais): array
    {
        $totais[$severidade]++;
        return [
            'tipo' => $tipo,
            'mensagem' => $mensagem,
            'severidade' => $severidade
        ];
    }
}