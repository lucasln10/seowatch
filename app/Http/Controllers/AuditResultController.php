<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\CrawlSeoData;
use App\Models\AuditResult;
use App\Models\Site;
use Illuminate\Http\Request;

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
        CrawlSeoData::dispatch($site->url, $site->id);

        $auditResult = AuditResult::where('site_id', $site->id)
                              ->with('auditMetrics') // Carrega as métricas relacionadas
                              ->orderBy('created_at', 'desc')
                              ->first();

        if (!$auditResult) {
            return view('audit.show', ['auditResult' => null, 'site' => $site, 'feedback' => []]);
        }

        $feedback = $auditResult->auditMetrics->transform(function ($metric) {
            $metric->feedback = json_decode($metric->feedback, true);
            return $metric;
        });

        return view('audit.show', compact('auditResult', 'site', 'feedback'));
    }
}