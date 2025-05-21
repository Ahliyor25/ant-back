<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'image' => url("storage/" . $this->image),
            'images' => ProductImageResource::collection($this->images),
            'sku' => $this->sku,
            'provider' => $this->provider,
            'category' => $this->category()->with('services')->get()[0],
            'subcategory' => $this->subcategory()->with('services')->get()[0] ?? null,
            'collection' => $this->collection,
            'slug' => $this->slug,
            'base_price' => $this->base_price,
            'unit' => $this->unit,
            'attributes' => ProductAttributeResource::collection($this->attributes),
            'discount' => $this->discount,
            'quantity' => $this->quantity,
            'product_type' => $this->productType,
            'is_active' => $this->is_active,
            'excluded_services' => ServiceResource::collection($this->services),
        ];
    }
}
