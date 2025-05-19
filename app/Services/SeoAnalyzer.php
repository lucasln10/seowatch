<?php

namespace App\Services;

use App\Models\AuditResult;
use App\Jobs\CrawlSeoData;

class SeoAnalyzer
{
    public function analyze(CrawlSeoData $crawlSeoData, AuditResult $auditResult)
    {
        $mensagens = [];

        // Título
        $title = $crawlSeoData->title;
        if (empty($title)) {
            $mensagens[] = [
                'tipo' => 'title',
                'mensagem' => 'Título ausente.',
                'severidade' => 'erro'
            ];
        } else {
            $titleLength = strlen($title);
            if ($titleLength < 50) {
                $mensagens[] = [
                    'tipo' => 'title',
                    'mensagem' => "Título muito curto ({$titleLength} caracteres).",
                    'severidade' => 'aviso'
                ];
            } elseif ($titleLength > 60) {
                $mensagens[] = [
                    'tipo' => 'title',
                    'mensagem' => "Título muito longo ({$titleLength} caracteres).",
                    'severidade' => 'aviso'
                ];
            } else {
                $mensagens[] = [
                    'tipo' => 'title',
                    'mensagem' => "Título com tamanho adequado ({$titleLength} caracteres).",
                    'severidade' => 'ok'
                ];
            }
        }

        // Meta description
        $description = $crawlSeoData->metaDescription;
        if (empty($description)) {
            $mensagens[] = [
                'tipo' => 'description',
                'mensagem' => 'Meta description ausente.',
                'severidade' => 'erro'
            ];
        } else {
            $descriptionLength = strlen($description);
            if ($descriptionLength < 120) {
                $mensagens[] = [
                    'tipo' => 'description',
                    'mensagem' => "Meta description muito curta ({$descriptionLength} caracteres).",
                    'severidade' => 'aviso'
                ];
            } elseif ($descriptionLength > 160) {
                $mensagens[] = [
                    'tipo' => 'description',
                    'mensagem' => "Meta description muito longa ({$descriptionLength} caracteres).",
                    'severidade' => 'aviso'
                ];
            } else {
                $mensagens[] = [
                    'tipo' => 'description',
                    'mensagem' => "Meta description em tamanho ideal ({$descriptionLength} caracteres).",
                    'severidade' => 'ok'
                ];
            }
        }

        // Headings: H1
        $h1 = $crawlSeoData->h1Texts;
        if (empty($h1)) {
            $mensagens[] = [
                'tipo' => 'h1',
                'mensagem' => 'H1 ausente.',
                'severidade' => 'erro'
            ];
        } elseif (count($h1) > 1) {
            $mensagens[] = [
                'tipo' => 'h1',
                'mensagem' => 'Múltiplos H1 encontrados (' . count($h1) . ').',
                'severidade' => 'aviso'
            ];
        } else {
            $mensagens[] = [
                'tipo' => 'h1',
                'mensagem' => 'H1 presente corretamente.',
                'severidade' => 'ok'
            ];
        }

        // Hierarquia de headings
        $h2 = $crawlSeoData->h2Texts;
        $h3 = $crawlSeoData->h3Texts;

        if (!empty($h1)) {
            if (!empty($h2)) {
                if (!empty($h3)) {
                    $mensagens[] = [
                        'tipo' => 'headings',
                        'mensagem' => 'Hierarquia de headings correta: H1 → H2 → H3.',
                        'severidade' => 'ok'
                    ];
                } else {
                    $mensagens[] = [
                        'tipo' => 'headings',
                        'mensagem' => 'H2 presente, mas H3 ausente.',
                        'severidade' => 'aviso'
                    ];
                }
            } elseif (!empty($h3)) {
                $mensagens[] = [
                    'tipo' => 'headings',
                    'mensagem' => 'H3 presente sem H2 — possível quebra de hierarquia.',
                    'severidade' => 'aviso'
                ];
            } else {
                $mensagens[] = [
                    'tipo' => 'headings',
                    'mensagem' => 'H1 presente, mas H2 e H3 ausentes.',
                    'severidade' => 'aviso'
                ];
            }
        }

        // Open Graph Title
        $ogTitle = $crawlSeoData->ogTitle;
        if (empty($ogTitle)) {
            $mensagens[] = [
                'tipo' => 'og:title',
                'mensagem' => 'Open Graph Title ausente.',
                'severidade' => 'erro'
            ];
        } else {
            $ogTitleLength = strlen($ogTitle);
            if ($ogTitleLength < 60) {
                $mensagens[] = [
                    'tipo' => 'og:title',
                    'mensagem' => "Open Graph Title muito curto ({$ogTitleLength} caracteres).",
                    'severidade' => 'aviso'
                ];
            } elseif ($ogTitleLength > 60) {
                $mensagens[] = [
                    'tipo' => 'og:title',
                    'mensagem' => "Open Graph Title muito longo ({$ogTitleLength} caracteres).",
                    'severidade' => 'aviso'
                ];
            } else {
                $mensagens[] = [
                    'tipo' => 'og:title',
                    'mensagem' => "Open Graph Title com tamanho ideal ({$ogTitleLength} caracteres).",
                    'severidade' => 'ok'
                ];
            }
        }

        // Canonical
        $canonical = $crawlSeoData->canonical;
        if (empty($canonical)) {
            $mensagens[] = [
                'tipo' => 'canonical',
                'mensagem' => 'Canonical ausente.',
                'severidade' => 'erro'
            ];
        } else {
            $mensagens[] = [
                'tipo' => 'canonical',
                'mensagem' => 'Canonical presente corretamente.',
                'severidade' => 'ok'
            ];
        }
        
        $auditResult->fedback = $mensagens;
        $auditResult->save();

        return $mensagens;
    }
}