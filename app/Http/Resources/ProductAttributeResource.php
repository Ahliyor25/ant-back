<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
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
            'product_id' => $this->product->id, // 'product' => $this->product,
            'attribute' => AttributeResource::make($this->attribute),
            'value' => $this->value,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sku' => $this->sku,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
