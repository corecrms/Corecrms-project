<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'country',
        'created_by',
        'updated_by',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function officeShifts()
    {
        return $this->hasMany(OfficeShift::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
