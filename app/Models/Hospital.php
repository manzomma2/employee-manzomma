<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    protected $fillable = ['name'];

    public function vacationHospitals(): HasMany
    {
        return $this->hasMany(VacationHospital::class);
    }
}
