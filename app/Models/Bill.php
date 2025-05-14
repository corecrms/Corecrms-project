<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';

    use HasFactory;
    protected $fillable = [
        'bill_number',
        'bill_date',
        'due_date',
        'vendor_id',
        'warehouse_id',
        'details',
        'discount',
        'tax',
        'grand_total',
        'status',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function ProductItems()
    {
        return $this->hasMany(BillProductItem::class);
    }
    public function BillPaymentHistory()
    {
        return $this->hasMany(BillPaymentHistory::class);
    }

    

}
