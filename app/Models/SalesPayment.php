<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'sales_payment';
    protected $fillable = [
        'customer_id',
        'account_id',
        'payment_method',
        'card_id',
        'cheque_no',
        'reciept_no',
        'payment_date',
        'status',
        'total_pay',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
        return $this->belongsTo(SavedCreditCard::class, 'card_id');
    }

    public function inoicePayment(){
        return $this->hasMany(SalesInvoicePayment::class, 'sales_payment_id');
    }

    public function invoicePayment(){
        return $this->hasMany(SalesInvoicePayment::class, 'sales_payment_id');
    }

}
