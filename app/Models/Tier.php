<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tier extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'discount', 'tier_type', 'created_by', 'updated_by', 'deleted_by'];


    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
