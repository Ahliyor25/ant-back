<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingType extends Model
{
    use HasFactory;

    public $fillable = [
        'key',
        'name',
        'icon',
        'description',
        'price',
        'is_active',
        'service_id',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
