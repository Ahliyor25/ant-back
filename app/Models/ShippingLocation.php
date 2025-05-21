<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingLocation extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'price',
        'is_active',
        'order',
        'service_id',
    ];

    public $casts = [
        'is_active' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->service->price,
        );
    }
}
