<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'ticket_id',
        'message',
        'attachment'
    ];

    public function customer(){
        return $this->belongsTo(User::class, 'customer_id');
    }
}
