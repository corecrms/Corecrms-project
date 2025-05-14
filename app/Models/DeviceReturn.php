<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'shipping_method',
        'tracking_number',
        'email',
        'reference_number',
        'address',
        'status',
        'comment',
        'imei_number',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
