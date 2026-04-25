<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'candidate_id',
        'job_posting_id',
        'match_percentage',
        'matched_skills',
        'missing_skills',
        'analysis_details',
        'status',
    ];

    protected $casts = [
        'match_percentage' => 'decimal:2',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_posting_id');
    }
}
