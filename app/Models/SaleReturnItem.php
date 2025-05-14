<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    use HasFactory;
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function sale_return(){
        return $this->belongsTo(SaleReturn::class,'sale_return_id');
    }
    public function sale_units(){
        return $this->belongsTo(Unit::class,'sale_unit');
    }
}
