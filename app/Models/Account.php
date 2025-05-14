<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'customer_id',
        'acc_no',
        'name',
        'init_balance',
        'details',
        'created_by',
        'updated_by',
        'deleted_by',
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
