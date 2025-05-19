<?php

namespace App\Jobs;

use App\Services\AuditService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AuditResult;

class RunAuditForSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siteId;

    public function __construct($siteId)
    {
        $this->siteId = $siteId;
    }

    public function handle(AuditService $auditService)
    {
        $auditResult = AuditResult::where('site_id', $this->siteId)
        ->latest()
        ->first();

        if (!$auditService) {
            $auditService->runAudit($auditResult->id);
        }
    }
}