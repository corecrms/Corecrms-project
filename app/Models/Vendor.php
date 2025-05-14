<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, Searchable;
    protected $table = 'vendors';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'company_name',
        'tax_number',
        'city',
        'country',
    ];
    protected $appends = ['total_due_invoice', 'total_paid_invoice', 'total_amount'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'vendor_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getTotalPaidInvoiceAttribute()
    {
        return $this->purchases()->sum('amount_recieved');
    }
    public function getTotalDueInvoiceAttribute()
    {
        return $this->purchases()->sum('amount_due');
    }
    public function getTotalAmountAttribute()
    {
        return $this->purchases()->sum('grand_total');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
    }

    public function nonInvoicePayments()
    {
        return $this->hasMany(NonPurchasePayment::class);
    }

    // public function nonInvoicePaymentsTotal()
    // {
    //     return $this->nonInvoicePayments()->where('payment_type','pay')->sum('amount_pay');
    // }
    // Method to get the total amount_pay where payment_type is 'pay'
    public function totalNonInvoicePaymentsPay()
    {
        return $this->nonInvoicePayments()->where('payment_type', 'pay');
    }

    // Method to get the total amount_pay where payment_type is 'due'
    public function totalNonInvoicePaymentsDue()
    {
        return $this->nonInvoicePayments()->where('payment_type', 'due');
    }

    public function totalDueAmount()
    {
        return $this->purchases()->sum('amount_due');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
