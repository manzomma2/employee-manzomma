<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VacationType extends Model
{
    protected $fillable = ['name'];

    public function vacations(): HasMany
    {
        return $this->hasMany(Vacation::class);
    }
}
