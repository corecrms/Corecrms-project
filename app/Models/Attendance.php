<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'time_in',
        'time_out',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    protected static function booted(): void
{
    static::addGlobalScope('latest', function (Builder $builder) {
        $builder->latest();
    });
}
}
