<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'link',
        'order',
        'type',
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
