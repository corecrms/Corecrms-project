<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BannerSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'created_by',
        'updated_by',
        'link'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
