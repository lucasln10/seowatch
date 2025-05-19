<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteDeleteController extends Controller
{
    public function destroy($id)
    {
        $site = Site::find($id);
        if(is_null($site)){
            return redirect('/index')->with('error','Site não encontrado.');
        }
        $site->delete();
        return redirect()->route('site.index')->with('success','Site deletado com sucesso.');
    }
}