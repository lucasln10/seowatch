<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditMetric extends Model
{
    protected $fillable = [
        'audit_result_id',
        'score',
        'fcp',
        'speed_index',
        'lcp',
        'cls',
        'feedback',
    ];

    protected $casts = [
            'feedback' => 'array', // Cast feedback to array
        ];

    public function auditResult()
    {
        return $this->belongsTo(AuditResult::class);
    }

}
