<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageHeading extends Model
{
    use HasFactory;

    protected $fillable = [
        'top_selling_product',
        'our_recomandation',
        'free_shipping_heading',
        'free_shipping_desc',
        'money_returns_heading',
        'money_returns_desc',
        'secure_payment_heading',
        'secure_payment_desc',
        'support_heading',
        'support_desc',
        'feature_category',
        'shop_by_brands',
    ];
}
