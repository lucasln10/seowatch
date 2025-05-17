<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


Route::get('/index', [SiteController::class, 'index'])->name('site.index');

Route::get('site/adicionar', [SiteController::class, 'adicionarSite'])->name('site.adicionar');
Route::post('site/adicionar', [SiteController::class, 'adicionar'])->name('site.adicionar');

Route::get('/site/editar/{id}', [SiteController::class, 'editarSite'])->name('site.editar');
Route::put('site/editar/{id}', [SiteController::class, 'update'])->name('site.update');

Route::delete('site/deletar/{id}', [SiteController::class,'deletar'])->name('site.deletar');

Route::get('/site/{id}', [SiteController::class,'mostrar'])->name('site.mostrar');