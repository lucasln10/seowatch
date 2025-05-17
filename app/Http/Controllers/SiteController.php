<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

use function Pest\Laravel\delete;
use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::all();
        return view('site.index', compact('sites'));
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
        return redirect('/index')->with('success','Site editado com sucesso.');
    }



    public function adicionarSite()
    {
        $sites = Site::find(1);
        return view('site.adicionar', compact('sites'));
    }

    public function adicionar(Request $request)
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

        $site = Site::create($validated);

        return redirect('/index')->with('success', 'Site adicionado com sucesso.');
    }

    public function editarSite($id)
    {
        $site = Site::find($id);
        if(is_null($site)){
            return redirect('/index')->with('error','Site não encontrado.');
        }
        return view('site.editar', compact('site'));
    }

    public function deletar(Request $request, $id)
    {
        $site = Site::find($id);
        if (is_null($site)) {
            return redirect('/index')->with('error','Site não encontrado.');
        }
        $site->delete();
        return redirect('/index')->with('success','Site excluido com sucesso.');
    }
}
