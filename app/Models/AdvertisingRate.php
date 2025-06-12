<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language;

class AdvertisingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'tariff_name',
        'discount_percent',
        'duration',
        'show_count',
        'price',
        'show_period',
        'daily_shows',
        'language_id',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeCurrentLang($query)
    {
        return $query->where('language_id', app('langId'));
    }

}
