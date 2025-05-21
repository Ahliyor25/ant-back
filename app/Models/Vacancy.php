<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Vacancy extends Model
{
    use HasFactory, Sluggable;

    public $fillable = [
        'name',
        'short_description',
        'description',
        'vacancy_category_id',
    ];

    public function vacancyCategory(): BelongsTo
    {
        return $this->belongsTo(VacancyCategory::class);
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        $category_id = $request->category_id;

        return $query->when($category_id, function (Builder $builder) use ($category_id) {
            $builder->where('vacancy_category_id', $category_id);
        });
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
