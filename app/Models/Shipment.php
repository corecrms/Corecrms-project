<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory , Searchable;

    public $fillable = [
        'reference',
        'sale_id',
        'date',
        'delivered_to',
        'address',
        'details',
        'status'
    ];

    public function sales(){
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['status'] = $this->status;
        return $array;
    }


}
