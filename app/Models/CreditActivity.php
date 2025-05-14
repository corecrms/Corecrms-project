<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'action',
        'credit_balance',
        'added_deducted',
        'comment'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
