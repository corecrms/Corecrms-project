<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTemplate extends Model
{


    use HasFactory;
    protected $table = 'sale_templates';

    protected $fillable = [
        'name',
        'type',
        'default_template',
        'created_by',
        'updated_by',
    ];
    protected $guarded = ['id'];

    public function getCreatedByAttribute($value)
    {
        return User::find($value)->name;
    }

    public function getUpdatedByAttribute($value)
    {
        return User::find($value)->name;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
    }
}
