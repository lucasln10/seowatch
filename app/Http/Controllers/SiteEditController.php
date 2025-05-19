<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;

class SiteEditController extends Controller
{
    public function edit($id)
    {
        $site = Site::find($id);
        return view('site.edit', compact('site'));
    }
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'title' => 'nullable|string|max:255',
            'url' => 'required|string|max:255|unique:sites,url,' . $id
            ],
            [
            'url.required' => 'O campo URL é obrigatório.',
            'url.url' => 'Digite uma URL válida.',
            'url.unique' => 'Este site já foi adicionado.',
            ]
        );

        $site = Site::findOrFail($id);
        $site->update($validate);
        return redirect()->route('site.index')->with('success','Site editado com sucesso.');
    }

}