<?php

namespace App\Models;

use App\Enums\Grade;
use App\Enums\GradeLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerProgression extends Model
{
    protected $fillable = [
        'employee_id',
        'job_grade',
        'job_level',
        'grade_effective_date',
        'grade_decision_number',
    ];

    protected $casts = [
        'grade_effective_date' => 'date:Y-m-d',
        'job_grade' => Grade::class,
        'job_level' => GradeLevel::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
}
