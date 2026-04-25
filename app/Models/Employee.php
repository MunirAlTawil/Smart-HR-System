<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'department',
        'salary',
        'hire_date',
        'employee_id',
        'notes',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get years of service
     */
    public function getYearsOfServiceAttribute()
    {
        if (!$this->hire_date) {
            return 0;
        }
        // Round to integer
        return (int) round($this->hire_date->diffInYears(now()));
    }

    /**
     * Get days of service
     */
    public function getDaysOfServiceAttribute()
    {
        if (!$this->hire_date) {
            return 0;
        }
        // Round to integer
        return (int) $this->hire_date->diffInDays(now());
    }
}
