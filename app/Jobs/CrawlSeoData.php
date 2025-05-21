<?php

namespace App\Jobs;

use App\Models\AuditResult;
use App\Services\SeoCrawler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlSeoData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected string $url;
    protected int $auditResultId;

    public function __construct(string $url, int $auditResultId)
    {
        $this->url = $url;
        $this->auditResultId = $auditResultId;
    }

    public function handle(SeoCrawler $seoCrawler): void
    {
        $seoData = $seoCrawler->extract($this->url);

        if (empty($seoData)) {
            return;
        }

        $auditResult = AuditResult::find($this->auditResultId);
        if (!$auditResult) {
            return;
        }

        $auditResult->update($seoData);
    }
}