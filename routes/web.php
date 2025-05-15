<?php

use App\Http\Controllers\AdicionarSiteController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


Route::get('/index', [SiteController::class, 'index']);

Route::get('site/adicionar', [AdicionarSiteController::class, 'adicionarSite']);
Route::post('site/adicionar', [AdicionarSiteController::class, 'adicionar']);
