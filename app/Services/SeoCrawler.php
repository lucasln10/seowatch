<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SeoCrawler
{
    public function extract(string $url): array
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