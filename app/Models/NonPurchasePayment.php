<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NonPurchasePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'non_purchase_payment';
    protected $fillable = [
        'vendor_id',
        'account_id',
        'cheque_no',
        'payment_type',
        'reciept_no',
        'payment_date',
        'status',
        'amount_pay',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
        'payment_method',
        'card_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
