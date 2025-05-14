<?php

namespace App\Models;

use PDO;
use App\Models\Shipment;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, Searchable;
    protected $guarded = ['id'];

    protected $casts = [
        'shopify_order_id' => 'integer', // Consider changing to 'string' if you face any issues
    ];

    protected $fillable = [
        'date',
        'product_id',
        'customer_id',
        'discount_id',
        'ntn',
        'order_tax',
        'discount',
        'shipping',
        'status',
        'payment_status',
        'payment_method',
        'amount_recieved',
        'amount_due',
        'amount_pay',
        'change_return',
        'bank_account',
        'grand_total',
        'notes',
        'shopify_order_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function productItems()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function sale_units()
    {
        return $this->hasOne(Unit::class, 'id', 'sale_unit');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function invoice()
    {
        return $this->hasOne(SaleInvoice::class, 'sale_id');
    }

    public function sale_return()
    {
        return $this->hasOne(SaleReturn::class, 'sale_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'sale_id');
    }

    public function getShopifyOrderIdAttribute($value)
    {
        return (int) $value;
    }

    public function setShopifyOrderIdAttribute($value)
    {
        $this->attributes['shopify_order_id'] = (string) $value;
    }

    public function shipingAddress()
    {
        return $this->hasOne(SaleShippingAddress::class, 'sale_id');
    }

    public function salePayment()
    {
        return $this->hasOne(SalesPayment::class, 'sale_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'bank_account');
    }


    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }

    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['date'] = $this->date;
        $array['reference'] = $this->reference;
        $array['ntn'] = $this->ntn;
        $array['order_tax'] = $this->order_tax;
        $array['shipping'] = $this->shipping;
        $array['status'] = $this->status;
        $array['payment_status'] = $this->payment_status;
        $array['payment_method'] = $this->payment_method;
        $array['amount_recieved'] = $this->amount_recieved;
        $array['amount_due'] = $this->amount_due;
        $array['amount_pay'] = $this->amount_pay;
        $array['change_return'] = $this->change_return;
        $array['bank_account'] = $this->bank_account;
        $array['grand_total'] = $this->grand_total;
        return $array;
    }
}
