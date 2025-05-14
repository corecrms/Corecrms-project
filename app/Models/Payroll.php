<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'employee_id',
        'account_id',
        'amount',
        'date',
        'payment_method',
        'payment_status',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
