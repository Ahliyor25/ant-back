<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Package extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'description',
        'image',
        'monthly_price',
        'yearly_price',
        'order',
        'language_id',
        'type_connection',
        'type',
        'discount',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeCurrentLang($query)
    {
        return $query->where('language_id', app('langId'));
    }

    public function package_attributes()
    {
    return $this->hasMany(PackageAttribute::class);
    }

    //find by type standard or discount
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
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
