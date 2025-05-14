<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_name',
        'unit_code',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'parent_id', 'id');
    }

    public function parentUnit()
    {
        return $this->belongsTo(Unit::class, 'parent_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }
    public function productsItem()
    {
        return $this->hasMany(ProductItem::class, 'sale_unit');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
