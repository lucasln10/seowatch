<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\CrawlSeoData;
use App\Models\AuditResult;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Jobs\RunAuditForSite;

class AuditResultController extends Controller
{
    public function index()
    {
        $auditResults = AuditResult::all();
        return view('audit.index', compact('auditResults'));
    }

    public function show($id)
    {
        $site = Site::findOrFail($id);
        $auditResult = AuditResult::where('site_id', $site->id)
                                ->with('auditMetrics')
                                ->orderBy('created_at', 'desc')
                                ->first();

        $feedback = $auditResult
        ? $auditResult->auditMetrics->transform(fn($metric) =>
            tap($metric, fn($m) => $m->feedback = is_string($m->feedback) ? json_decode($m->feedback, true) : $m->feedback))
        : collect();

        return view('audit.show', compact('auditResult', 'site', 'feedback'));
    }

    public function runAudit($id)
    {
        $site = Site::findOrFail($id);
        RunAuditForSite::dispatch($site->id);
        return redirect()->route('audit.show', $site->id)
            ->with('status', 'Auditoria iniciada e será processada em segundo plano.');
    }
}