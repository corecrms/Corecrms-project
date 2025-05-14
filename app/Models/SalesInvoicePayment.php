<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesInvoicePayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'sales_invoice_payment';
    protected $fillable = [
        'sale_invoice_id',
        'sales_payment_id',
        'paid_amount'
    ];

    public function salesPayment()
    {
        return $this->belongsTo(SalesPayment::class, 'sales_payment_id');
    }
    public function saleInvoice()
    {
        return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id');
    }

    // public function salesInvoice()
    // {
    //     return $this->belongsTo(SalesInvoice::class);
    // }

    // public function salesInvoiceCreditNotes()
    // {
    //     return $this->hasMany(SalesInvoiceCreditNotes::class);
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
