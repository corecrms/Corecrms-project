<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;
    protected $fillable = [
        'warranty_name',
        'warranty_type',
        'warranty_period',
        'warranty_description',
    ];
}
