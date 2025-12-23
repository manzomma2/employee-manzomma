<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'job_group_id'];

    public function jobGroup()
    {
        return $this->belongsTo(JobGroup::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'category_group_id');
    }
}
