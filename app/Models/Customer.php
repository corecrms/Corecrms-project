<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'status',
        'city',
        'country',
        'tax_number',
        'balance',
        'business_name',
        'outstanding_balance',
    ];

    protected $appends = ['total_due_invoice', 'total_paid_invoice', 'total_amount'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creditActivities()
    {
        return $this->hasMany(CreditActivity::class);
    }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'customer_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'customer_id');
    }

    public function tiers()
    {
        return $this->belongsTo(Tier::class, 'tier_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    // sales has a invoice i want to get the total amount of all invoices
    public function getTotalPaidInvoiceAttribute()
    {
        return $this->sales()->sum('amount_recieved');
    }
    public function getTotalDueInvoiceAttribute()
    {
        return $this->sales()->sum('amount_due');
    }
    public function getTotalAmountAttribute()
    {
        return $this->sales()->sum('grand_total');
    }

    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        // $array['status'] = $this->status;
        // $array['user'] = $this->user;
        return $array;
    }

    public function nonInvoicePayments()
    {
        return $this->hasMany(NonSalesPayment::class);
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

    // public function account()
    // {
    //     return $this->hasOne(Account::class);
    // }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }


    public function totalDue()
    {
        return $this->sales()->sum('amount_due');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
