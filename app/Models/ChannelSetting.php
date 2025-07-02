<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file',
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
