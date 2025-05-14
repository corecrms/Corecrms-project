<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;

    public function from_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function to_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function transfer_products()
    {
        return $this->hasMany(TransferProductItem::class, 'transfer_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
