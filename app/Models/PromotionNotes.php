<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'content',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
