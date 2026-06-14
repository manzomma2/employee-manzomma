<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vacation extends Model
{
    protected $fillable = [
        'employee_id',
        'vacation_type_id',
        'start_date',
        'end_date',
        'pre_end_date',
        'notes',
        'extension_notes',
        'cut_note',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'pre_end_date' => 'date:Y-m-d',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function vacationType(): BelongsTo
    {
        return $this->belongsTo(VacationType::class);
    }

    public function vacationHospital(): HasOne
    {
        return $this->hasOne(VacationHospital::class);
    }
}
