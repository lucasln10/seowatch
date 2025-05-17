<?php

namespace App\Jobs;

use DOMDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class CrawlSeoData implements ShouldQueue
{
    use Queueable;

    protected $url;
    /**
     * Create a new job instance.
     */

    public function __construct($url)
    {
        $this->url = $url;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $html = Http::get($this->url)->body();
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $dom->getElementsByTagName('title')->item(0)->nodeValue();
        $dom->getElementsByTagName('meta');
        $dom->getElementsByTagName('link');
        $dom->getElementsByTagName('h1');

    }
}
