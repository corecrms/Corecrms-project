<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Barcode extends Model
{
    use HasFactory ,Searchable;

    protected $guarded = ['id'];
    protected $fillable = ['product_id', 'symbology', 'code'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['code'] = $this->code;
        return $array;
    }
}
