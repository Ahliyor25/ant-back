<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Promotion;

class PromotionDraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'title',
        'description'
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
