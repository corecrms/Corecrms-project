<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Searchable;
    protected $guarded = ['id'];

    protected $casts = [
        'shopify_id' => 'integer', // Consider changing to 'string' if you face any issues
    ];

    protected $fillable = [
        'name',
        'sku',
        'description',
        'unit_id',
        'category_id',
        'sub_category_id',
        'brand_id',
        'barcode',
        'quantity',
        'warranty_id',
        'condition',
        'tax_applicable',
        'tax_info',
        'purchase_price',
        'sell_price',
        'stock_alert',
        'product_unit',
        'sale_unit',
        'purchase_unit',
        'tax_type',
        'product_type',
        'order_tax',
        'status',
        'imei_no',
        'warehouse_id',
        'shopify_id',
        'product_live',
        'new_product',
        'featured_product',
        'best_seller',
        'recommended',
        'created_by',
        'updated_by',
        'deleted_by',
        'product_weight_unit',
        'product_weight',
        'product_dimension_unit',
        'product_length',
        'product_width',
        'product_height',
        'product_imei_no',
        'ailse',
        'rack',
        'shelf',
        'bin',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_type');
    }


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'product_unit');
    }

    public function sale_units()
    {
        return $this->belongsTo(Unit::class, 'sale_unit');
    }

    public function purchase_unit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class);
    }
    // public function warehouses()
    // {
    //     return $this->belongsToMany(Warehouse::class, 'product_warehouses')->withTimestamps();
    // }
    public function getShopifyIdAttribute($value)
    {
        return (int) $value;
    }

    public function setShopifyIdAttribute($value)
    {
        $this->attributes['shopify_id'] = (string) $value;
    }

    public function product_warehouses()
    {
        return $this->hasMany(ProductWarehouse::class, 'product_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function sales()
    {
        return $this->hasMany(Purchase::class);
    }

    public function product_items()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function purchase_items()
    {
        return $this->hasMany(PurchaseProductItem::class);
    }
    public function sale_return_items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }
    public function purchase_return_items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }
    public function transfer_items()
    {
        return $this->hasMany(TransferProductItem::class);
    }

    public function product_inventory()
    {
        return $this->hasMany(ProductInventory::class);
    }


    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['name'] = $this->name;
        $array['sku'] = $this->sku;
        $array['description'] = $this->description;
        $array['barcode'] = $this->barcode;
        $array['purchase_price'] = $this->purchase_price;
        $array['sell_price'] = $this->sell_price;
        $array['category_id'] = $this->category->name ?? null;
        $array['brand_id'] = $this->brand_id;
        $array['stock_alert'] = $this->stock_alert;
        $array['product_type'] = $this->product_type;
        $array['order_tax'] = $this->order_tax;
        $array['status'] = $this->status;
        $array['product_unit'] = $this->product_unit;
        $array['sale_unit'] = $this->sale_unit;
        $array['warehouse_id'] = $this->warehouse_id;
        // $array['description'] = $this->description;
        return $array;
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }


    public function totalQuantity()
    {
        return $this->product_warehouses->sum('quantity');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
