<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_domain',
        'access_token',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
