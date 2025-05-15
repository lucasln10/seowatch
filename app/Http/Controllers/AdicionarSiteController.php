<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use App\Http\Requests;

class AdicionarSiteController extends Controller
{
    public function adicionarSite()
    {
        $site = Site::find(1);
        return view('site.adicionar', compact('site'));
    }

    public function adicionar(Request $request)
    {
        $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'url' => 'required|string|max:255'
        ]);

        $site = Site::create($validated);

        return redirect('/index')->with('success','Site adicionado com sucesso.');
    }
}