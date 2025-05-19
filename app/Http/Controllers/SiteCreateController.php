<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;


class SiteCreateController extends Controller
{
    public function create()
    {
        return view('site.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
            'title' => 'nullable|string|max:255',
            'url' => 'required|string|max:255|unique:sites,url,'
            ],
            [
            'url.required' => 'O campo URL é obrigatório.',
            'url.url' => 'Digite uma URL válida.',
            'url.unique' => 'Este site já foi adicionado.',
            ]
        );

        $url = $validated['url'];
        if (!preg_match('/^https?:\/\//', $url)){
            $url = 'https://' . $url;
        }

        $validated['url'] = $url;

        $site = Site::create($validated);

        return redirect()->route('site.index')->with('success', 'Site adicionado com sucesso.');
    }
}