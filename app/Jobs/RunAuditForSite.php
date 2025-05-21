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
        try {
            $auditResult = AuditResult::create([
                'site_id' => $this->siteId,
                'status' => 'pending',
            ]);

            if (!$auditResult || !$auditResult->id) {
                throw new \Exception("Falha ao criar AuditResult para site_id {$this->siteId}");
            }

            $auditService->runAudit($auditResult);
        } catch (\Exception $e) {
            \Log::error("Erro ao rodar auditoria para site_id {$this->siteId}: " . $e->getMessage());

            if (isset($auditResult) && $auditResult->id) {
                $auditResult->update(['status' => 'failed']);
            }
        }
    }
}