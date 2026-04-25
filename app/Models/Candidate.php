<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cv_path',
        'cv_text',
        'approval_status',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
