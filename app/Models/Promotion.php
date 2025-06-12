<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Promotion extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'description',
        'image',
        'published_at',
        'type',
        'order',
        'language_id',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeCurrentLang($query)
    {
        return $query->where('language_id', app('langId'));
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    public function scopeOrdered($query){
   
        return $query->orderBy('order');
        
    }
}
