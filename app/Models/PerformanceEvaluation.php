<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_date',
        'to_date',
        'degree',
        'rating',
        'employee_id'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
