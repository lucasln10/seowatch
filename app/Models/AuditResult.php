<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AuditResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'title',
        'meta_description',
        'og_title',
        'canonical',
        'h1',
        'h2',
        'h3',
        'status',
    ];

    protected $casts = [
    'feedback' => 'array',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function auditMetrics()
    {
        return $this->hasMany(AuditMetric::class);
    }
}
