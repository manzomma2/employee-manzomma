<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CategoryGroup;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'hire_date' => 'date:Y-m-d',
        'contract_date' => 'date:Y-m-d',
        'joining_date' => 'date:Y-m-d',
        'grade_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class)->with('jobGroup');
    }
}
