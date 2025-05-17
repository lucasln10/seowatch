<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- esta linha
use App\Models\AuditResult;

class Site extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url'];

    public function auditResults(): HasMany
    {
        return $this->hasMany(AuditResult::class);
    }
}

