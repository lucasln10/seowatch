<?php

namespace App\Jobs;

use DOMDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use App\Jobs\AuditResult;

class CrawlSeoData implements ShouldQueue
{
    use Queueable;

    protected $siteId;
    protected $url;
    /**
     * Create a new job instance.
     */

    public function __construct($url, $siteId)
    {
        $this->url = $url;
        $this->siteId = $siteId;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $respose = Http::get($this->url);

        if ($respose->failed()) {
            return;
        }
        $html = $respose->body();
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $titleNode = $dom->getElementsByTagName('title')->item(0);
        $title = $titleNode ? trim($titleNode->textContent) : null;

        $metaDescriptionNode = $xpath->query('//meta[@name="description"]')->item(0);
        $metaDescription = $metaDescriptionNode ? trim($metaDescriptionNode->getAttribute('content')) : null;

        $ogTitleNode = $xpath->query('//meta[@property="og:title"]')->item(0);
        $ogTitle = $ogTitleNode ? trim($ogTitleNode->getAttribute('content')) : null;

        $canonicalNode = $xpath->query('//link[@rel="canonical"]')->item(0);
        $canonical = $canonicalNode ? trim($canonicalNode->getAttribute('href')) : null;

        $h1Nodes = $dom->getElementsByTagName('h1');
        $h2Nodes = $dom->getElementsByTagName('h2');
        $h3Nodes = $dom->getElementsByTagName('h3');

        $h1Texts = [];
        foreach ($h1Nodes as $node) {
            $h1Texts[] = trim($node->textContent);
        }
        $h2Texts = [];
        foreach ($h2Nodes as $node) {
            $h2Texts[] = trim($node->textContent);
        }
        $h3Texts = [];
        foreach ($h3Nodes as $node) {
            $h3Texts[] = trim($node->textContent);
        }


        AuditResult::create([
            'site_id' => $this->siteId,
            'title' => $title,
            'meta_description' => $metaDescription,
            'og_title' => $ogTitle,
            'canonical' => $canonical,
            'h1' => $h1Texts ? implode(' | ', $h1Texts) : null,
            'h2' => $h2Texts ? implode(' | ', $h2Texts) : null,
            'h3' => $h3Texts ? implode(' | ', $h3Texts) : null,
        ]);
    }
}
