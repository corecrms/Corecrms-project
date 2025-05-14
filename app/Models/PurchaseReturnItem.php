<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    // public function purchase_unit(){
    //     return $this->belongsTo(Unit::class,'purchase_unit');
    // }
    public function purchase_units(){
        return $this->belongsTo(Unit::class,'purchase_unit');
    }
    public function purchase_return(){
        return $this->belongsTo(PurchaseReturn::class,'purchase_return_id');
    }
}
