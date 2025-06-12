<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'promotion_draw_id',
        'promotion_prize_id',
        'region_id',
        'full_name',
        'month',
        'prize_type',
        'image',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function promotionDraw()
    {
        return $this->belongsTo(PromotionDraw::class);
    }

    public function promotionPrize()
    {
        return $this->belongsTo(PromotionPrize::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
