<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingLocationResource extends JsonResource
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
            'name' => $this->name,
            'price' => $price,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'service' => $this->service,
        ];
    }
}
