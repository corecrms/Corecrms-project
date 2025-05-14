<?php

namespace App\Models;

use Laravel\Scout\Searchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    protected $table = 'brands';

    protected $guarded = ['id'];
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'brand_img', 'description', 'created_by', 'updated_by'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getCreatedByAttribute($value)
    {
        return User::find($value)->name ?? '';
    }

    public function getUpdatedByAttribute($value)
    {
        return User::find($value)->name ?? '';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['name'] = $this->name;
        return $array;
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    // Query Scope
    //     protected static function booted(): void
    //     {
    //         static::addGlobalScope('latest', function (Builder $builder) {
    //             $builder->latest();
    //         });
    //     }

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('latest', function (Builder $builder) {
    //         $builder->latest();
    //     });
    // }
}
