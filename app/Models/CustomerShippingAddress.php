<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone_no',
        'address',
        'address_line_2',
        'city',
        'state',
        'state_code',
        'country',
        'country_code',
        'postal_code',
        'is_default',
    ];
}
