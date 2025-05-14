<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleShippingAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'name',
        'company_name',
        'email',
        'contact_no',
        'address',
        'appartment',
        'city',
        'country',
        'state',
        'zip_code',
        'notes',
        'status'];
}
