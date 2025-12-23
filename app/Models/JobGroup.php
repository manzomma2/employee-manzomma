<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class JobGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function categoryGroups()
    {
        return $this->hasMany(CategoryGroup::class);
    }
}
