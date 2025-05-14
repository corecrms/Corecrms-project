<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepositCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->deposits()->sum('amount');
    }
    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
