<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'review', 'product_id', 'rating'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
