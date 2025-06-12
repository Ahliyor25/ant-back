<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpFile extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'title',
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
