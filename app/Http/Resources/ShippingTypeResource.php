<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = $this->price;
        if ($this->service) {
            if ($this->service->price !== null) {
                $price = $this->service->price;
            }
        }
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'icon' => url('storage/' . $this->icon),
            'description' => $this->description,
            'price' => $price,
            'service' => $this->service,
            'is_active' => $this->is_active,
        ];
    }
}
