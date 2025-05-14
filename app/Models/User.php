<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, Billable, SoftDeletes, Searchable;

    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'image',
        'email',
        'password',
        'facebook_id',
        'google_id',
        'facebook_token',
        'facebook_token_expiry',
        'type',
        'contact_no',
        'username',
        'address',
        'status',
        'warehouse_id',
        'country_code',
        'state_code',
        'postal_code',
        'address_line_2',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class);
    }

    public function subscriptionPackage()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    // public function customers()
    // {
    //     return $this->hasMany(Customer::class);
    // }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'created_by');
    }

    public function salesInvoicePayments()
    {
        return $this->hasMany(SalesInvoicePayment::class);
    }
    public function shopify_store()
    {
        return $this->hasOne(ShopifyStore::class);
    }


    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['name'] = $this->name;
        $array['email'] = $this->email;
        return $array;
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(AddToCart::class, 'customer_id');
    }

    public function savedCards()
    {
        return $this->hasMany(SavedCreditCard::class, 'user_id');
    }
    public function adminCreditCards()
    {
        return $this->hasMany(AdminCreditCard::class, 'user_id');
    }


    public function assignWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }

    public function shippingAddresses()
    {
        return $this->hasMany(CustomerShippingAddress::class, 'customer_id');
    }

    
}
