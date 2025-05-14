<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    use HasFactory;

    public function products(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function inventory(){
        return $this->belongsTo(Inventory::class,'inventory_id');
    }
}
