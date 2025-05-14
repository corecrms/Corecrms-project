<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseInvoicePayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'purchase_invoice_payment';
    protected $fillable = [
        'purchase_invoice_id',
        'purchase_payment_id',
        'paid_amount'
    ];
    public function purchasePayment()
    {
        return $this->belongsTo(PurchasePayment::class, 'purchase_payment_id');
    }
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    // public function purchaseInvoice()
    // {
    //     return $this->belongsTo(PurchaseInvoice::class);
    // }

    // public function purchaseInvoiceCreditNotes()
    // {
    //     return $this->hasMany(PurchaseInvoiceCreditNotes::class);
    // }

    // public function getPaymentMethodAttribute($value)
    // {
    //     return $value == 1 ? 'Cash' : 'Cheque';
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
