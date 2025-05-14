<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_head',
        'company_id',
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class,'department_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function departmentHead(){
        return $this->belongsTo(Employee::class, 'department_head');
    }



    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
