<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBill extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_id','vendor_id','type','pay_to','email','zip_code','state','account_number','routing_number','bank_name','street_address','full_address','amount','date'];
    
}
