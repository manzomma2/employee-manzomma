<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'decision',
        'value',
        'date',
        'employee_id'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
