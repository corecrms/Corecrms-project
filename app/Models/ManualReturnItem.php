<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualReturnItem extends Model
{
    use HasFactory;

    public function return_unit(){
        return $this->belongsTo(Unit::class,'return_unit');
    }
    public function return_units(){
        return $this->belongsTo(Unit::class,'return_unit');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }


}
