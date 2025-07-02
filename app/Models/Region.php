<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Region extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'order',
        'language_id',
        'latitude',
        'longitude',
    ];

    public function centers() {
        return $this->hasMany(ServiceCenter::class);
    }

    
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
                'source' => 'name',
            ]
        ];
    }

}
