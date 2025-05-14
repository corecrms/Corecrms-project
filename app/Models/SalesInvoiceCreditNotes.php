<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceCreditNotes extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function salesInvoicePayment()
    {
        return $this->belongsTo(SalesInvoicePayment::class);
    }

    public function getPaymentMethodAttribute($value)
    {
        return $value == 1 ? 'Cash' : 'Cheque';
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
