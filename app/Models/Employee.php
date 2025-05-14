<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'department_id',
        'designation_id',
        'office_shift_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'joining_date',
        'country',
        'dob',
        'gender',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function officeShift()
    {
        return $this->belongsTo(OfficeShift::class);
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
