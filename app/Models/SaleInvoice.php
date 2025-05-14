<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleInvoice extends Model
{
    use HasFactory;


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function sale(){
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function saleInvoicePayment(){
        return $this->hasMany(SalesInvoicePayment::class,'sale_invoice_id');
    }

}
