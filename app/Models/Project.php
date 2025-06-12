<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory, Sluggable;

    public $fillable = [
        'title',
        'image',
        'slug',
        'short_description',
        'description',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function deleteImages()
    {
        Storage::delete(array_column(
            $this->images()->get()->toArray(),
            'image'
        ));
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }
}
