<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Stripe\ApiOperations\Search;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory, Searchable;

    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'phone',
        'email',
        'country',
        'city',
        'zip_code',
        'address',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function productWarehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class)->with('purchase_return');
    }
    
    public function purchase_returns()
    {
        // purchase_return table has purchase_id column and purchases table has warehouse_id column
        return $this->hasMany(PurchaseReturn::class, 'purchase_id', 'purchase_id')->where('purchases.warehouse_id', $this->id);
    }
    public function sale_returns()
    {
        return $this->hasMany(SaleReturn::class, 'sale_id', 'sale_id')->where('sales.warehouse_id', $this->id);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('latest', function (Builder $builder) {
    //         $builder->latest();
    //     });
    // }
}
