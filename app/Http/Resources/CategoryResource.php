<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'icon' => url('storage/' . $this->icon),
            'order' => $this->order,
            'subcategories' => SubcategoryResource::collection($this->subcategories),
            'services' => ServiceResource::collection($this->services),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
