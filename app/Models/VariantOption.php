<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;
    protected $fillable = [ 'variant_id', 'sub_name', 'code', 'cost', 'price'];
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
