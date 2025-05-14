<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleReturn extends Model
{
    use HasFactory;

    public function return_items()
    {
        return $this->hasMany(SaleReturnItem::class, 'sale_return_id');
    }
    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
