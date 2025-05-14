<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'price',
        'warehouse_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
