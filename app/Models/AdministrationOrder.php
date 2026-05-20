<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministrationOrder extends Model
{
    protected $fillable = [
        'employee_id',
        'sector_id',
        'department_id',
        'work_job',
        'order_date',
        'inform_date',
        'transfer_date',
        'combine_date',
        'active'
    ];

    protected $casts = [
        'order_date' => 'date',
        'inform_date' => 'date',
        'transfer_date' => 'date',
        'combine_date' => 'date',
        'active' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
