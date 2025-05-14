<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_brand',
        'card_last_four',
        'card_exp_month',
        'card_exp_year',
        'user_id',
        
    ];
}
