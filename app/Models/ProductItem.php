<?php

namespace App\Models;

use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'product_id',
        'sale_id',
        'quantity',
        'price',
        'tax_type',
        'sale_unit',
        'discount_type',
        'discount',
        'order_tax',
        'sub_total',
        'stock',
    ];


    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function sale_unit(){
    //     return $this->belongsTo(Unit::class,'sale_unit');
    // }

    public function sale_units(){
        return $this->belongsTo(Unit::class,'sale_unit');
    }

    //sale unit has unit_id in product item table
    public function unit(){
        return $this->belongsTo(Unit::class, 'sale_unit', 'id');
    }

    public function variants(){

        //product_items table has product_id, variants table has product_id and variant_options table has variant_id, so we need to get variants of each product item
        return $this->belongsToMany(Variant::class, 'variants', 'product_id', 'product_id')
            ->join('variant_options', 'variants.id', '=', 'variant_options.variant_id')
            ->where('product_items.product_id', '=', $this->product_id)
            ->select('variants.*')
            ->withPivot('variant_option_id');
    }

    // public function warehouse(){
    //     return $this->sale->warehouse;
    // }
    public static function topSellingProducts($limit = 10)
    {
        return DB::table('product_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->join('products', 'product_items.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take($limit)
            ->get();
    }

}
