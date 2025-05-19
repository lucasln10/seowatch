<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditResult;
use App\Services\AuditService;

class PageSpeedController extends Controller
{
    public function pageSpeed(AuditService $auditService, $auditResultId)
    {
        $result = $auditService->runAudit($auditResultId);
        return response()->json($result);
    }

    public function showReport($auditResultId)
    {
        $auditResult = AuditResult::with('auditMetrics', 'site')->findOrFail($auditResultId);
        $metrics = $auditResult->auditMetrics->last(); // pega a métrica mais recente
        return view('audit.show', compact('auditResult',    'metrics'));
    }
}
