<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'name'];
    
    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
