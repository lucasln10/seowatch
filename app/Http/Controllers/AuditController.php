<?php

namespace App\Http\Controllers;

use App\Models\AuditResult;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "url" => "required|url"
        ]);


        $url = $request->input('url');
        $host = parse_url($url, PHP_URL_HOST);

        $site = Site::firstOrCreate(
            ['domain' => $host],
            ['url' => $url]
        );


        $auditoria = AuditResult::create([
            'site_id' => $site->id,
            'title' => null,
            'meta_description' => null,
            'status' => 'processando',
        ]);


        return redirect()->back()->with([
            'message' => 'Auditoria iniciada com sucesso.',
            'audit_result_id' => $auditoria->id,
        ]);
    }
}
