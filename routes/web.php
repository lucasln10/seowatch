<?php

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteCreateController;
use App\Http\Controllers\SiteEditController;
use App\Http\Controllers\SiteDeleteController;
use App\Http\Controllers\AuditResultController;
use App\Http\Controllers\PageSpeedController;
use App\Models\AuditMetric;
use Illuminate\Support\Facades\Route;




Route::get('/index', [SiteController::class, 'index'])->name('site.index');

Route::get('/sites/criar', [SiteCreateController::class, 'create'])->name('site.create');
Route::post('/sites', [SiteCreateController::class, 'store'])->name('site.store');

Route::get('/sites/{id}/editar', [SiteEditController::class, 'edit'])->name('site.edit');
Route::put('/sites/{id}', [SiteEditController::class, 'update'])->name('site.update');

Route::delete('/sites/{id}', [SiteDeleteController::class,'destroy'])->name('site.destroy');

Route::get('/audit', [AuditResultController::class,'index'])->name('audit.index');
Route::get('/audit/{id}', [AuditResultController::class, 'show'])->name('audit.show');
Route::post('/audit/{id}/run', [AuditResultController::class, 'runAudit'])->name('audit.run');

Route::get('/pagespeed/{auditResultId}', [PageSpeedController::class, 'pageSpeed'])->name('pagespeed.pageSpeed');

Route::get('/relatorio/{auditResultId}', [PageSpeedController::class, 'showReport'])->name('audit.report');