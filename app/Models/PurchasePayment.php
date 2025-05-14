<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'purchase_payment';
    protected $fillable = [
        'vendor_id',
        'account_id',
        'cheque_no',
        'reciept_no',
        'payment_date',
        'status',
        'total_pay',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
        'card_id',
        'payment_method',

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


    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function invoicePayment()
    {
        return $this->hasMany(PurchaseInvoicePayment::class, 'purchase_payment_id');
    }
}
