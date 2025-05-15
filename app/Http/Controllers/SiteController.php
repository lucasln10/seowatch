<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;


use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::all();
        return view('site.index', compact('sites'));
    }
}
