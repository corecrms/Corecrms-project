<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPaymentHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'bill_id',
        'account_id',
        'amount',
        'description',
    ];
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }
}
