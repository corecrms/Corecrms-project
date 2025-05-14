<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'date',
        'product_id',
        'vendor_id',
        'discount_id',
        'ntn',
        'order_tax',
        'discount',
        'shipping',
        'status',
        'payment_status',
        'payment_method',
        'amount_recieved',
        'amount_pay',
        'amount_due',
        'change_return',
        'bank_account',
        'grand_total',
        'notes',
        'reference',
        'warehouse_id',
        'created_by',
        'updated_by',

    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseProductItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(PurchaseInvoice::class, 'purchase_id');
    }

    public function purchase_return()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    public function bill()
    {
        return $this->hasOne(PayBill::class, 'purchase_id');
    }


    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['date'] = $this->date;
        $array['reference'] = $this->reference;
        $array['status'] = $this->status;
        $array['payment_status'] = $this->payment_status;
        $array['payment_method'] = $this->payment_method;
        $array['amount_recieved'] = $this->amount_recieved;
        $array['grand_total'] = $this->grand_total;
        $array['amount_due'] = $this->amount_due;
        $array['ntn'] = $this->ntn;
        $array['order_tax'] = $this->order_tax;

        return $array;
    }


    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'bank_account');
    }
}
