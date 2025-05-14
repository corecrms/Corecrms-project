<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseInvoiceDetails()
    {
        return $this->hasMany(PurchaseInvoiceDetail::class, 'purchase_invoice_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }


}
