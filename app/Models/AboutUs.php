<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;
    protected $table = 'about_us';
    protected $fillable = [
        'section_1_title',
        'section_1_desc',
        'section_1_image',
        'section_2_title',
        'section_2_desc',
        'section_2_image',
        'our_services',
    ];
}
