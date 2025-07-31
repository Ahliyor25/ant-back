<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportPageText extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeCurrentLang($query)
    {
        return $query->where('language_id', app('langId'));
    }
}
