<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class VacancyCategory extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    public function scopeFilter(Builder $builder, Request $request)
    {
        if (!$request->filter) {
            return $builder;
        }
        return $builder->whereHas('vacancies');
    }
}
