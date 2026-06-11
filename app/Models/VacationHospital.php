<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacationHospital extends Model
{
    protected $fillable = [
        'vacation_id',
        'hospital_id',
        'diagnoses',
    ];

    public function vacation(): BelongsTo
    {
        return $this->belongsTo(Vacation::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }
}
