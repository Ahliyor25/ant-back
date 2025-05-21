<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Http\Request;

class Product extends Model
{
    use HasFactory, Sluggable;

    public $fillable = [
        'id',
        'name',
        'description',
        'image',
        'sku',
        'slug',
        'provider_id',
        'category_id',
        'subcategory_id',
        'collection_id',
        'base_price',
        'discount',
        'quantity',
        'product_type_id',
        'unit',
        'is_active'
    ];

    public $casts = [
        'is_active' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $search = $request->search;
        if (empty($search)) {
            return $query->where('id', null);
        }
        return $query->where('name', 'like', "%" . $search . "%")
            ->orWhere('sku', 'like', "%" . $search . "%")
            ->orWhere('slug', 'like', "%" . $search . "%");
    }

    public function scopeFilterQuantity(Builder $query, Request $request)
    {
        $isFilter = $request->has('filter_quantity');
        if (!$isFilter) {
            return $query;
        }
        return $query->where('quantity', '>', 0);
    }



    public function scopeFilterActive(Builder $query, Request $request)
    {
        $isFilter = $request->has('filter_active');
        if (!$isFilter) {
            return $query;
        }
        return $query->where('is_active',  1);
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        $categories = $request->category_id;
        $subcategories = $request->subcategory_id;
        $providers = $request->provider_id;
        $collections = $request->collection_id;
        $product_type = $request->product_type;
        $search = $request->search;

        return $query
            ->when($search, function (Builder $query) use ($search) {
                $query->where('name', 'like', "%" . $search . "%")
                    ->orWhere('sku', 'like', "%" . $search . "%")
                    ->orWhere('slug', 'like', "%" . $search . "%");
            })
            ->when($categories, function (Builder $query) use ($categories) {
                $query->whereIn('category_id', $categories);
            })
            ->when($subcategories, function (Builder $query) use ($subcategories) {
                $query->whereIn('subcategory_id', $subcategories);
            })
            ->when($providers, function (Builder $query) use ($providers) {
                $query->whereIn('provider_id', $providers);
            })
            ->when($collections, function (Builder $query) use ($collections) {
                $query->whereIn('collection_id', $collections);
            })
            ->when($product_type, function (Builder $query) use ($product_type) {
                $query->whereHas('productType', function (Builder $query) use ($product_type) {
                    $query->where('product_types.key', $product_type);
                })->with('productType');
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
