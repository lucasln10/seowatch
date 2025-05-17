<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url'];

    public function auditResult()
    {
        return $this->hasOne(AuditResult::class);
    }
}

