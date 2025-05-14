<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{

    protected $table = 'categories';

    protected $guarded = ['id'];
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'brand_id',
        'name',
        'code',
        'description',
        'created_by',
        'updated_by',
        'status',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function getCreatedByAttribute($value)
    {
        return User::find($value)->name ?? 'N/A';
    }

    public function getUpdatedByAttribute($value)
    {
        return User::find($value)->name ?? 'N/A';
    }

    // public function getStatusAttribute($value)
    // {
    //     return $value == 1 ? 'Active' : 'Inactive';
    // }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['name'] = $this->name;
        $array['code'] = $this->code;
        return $array;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
