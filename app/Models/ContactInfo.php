<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_phone',
        'contact_email',
        'youtube',
        'facebook',
        'instagram',
    ];
}
