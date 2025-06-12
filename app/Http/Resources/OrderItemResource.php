<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_sku' => $this->product_sku,
            'product_base_price' => $this->product_base_price,
            'product_unit' => $this->product_unit,
            'quantity' => $this->quantity,
            'attributes' => $this->attributes,
            'provider_name' => $this->provider_name,
            'collection_name' => $this->collection_name,
            'category_name' => $this->category_name,
            'subcategory_name' => $this->subcategory_name,
            'product_discount' => $this->product_discount,
            'product_image' => url('storage/' . $this->product_image),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
