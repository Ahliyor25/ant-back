<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PackageResource extends JsonResource
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
            'image' => url('storage/' . $this->image),
            'monthly_price' => $this->monthly_price,
            'yearly_price' => $this->yearly_price,
            'order' => $this->order,
            'attributes' => PackageAttrResource::collection($this->package_attributes),
            'slug' => $this->slug,
            'created_at' => $this->created_at,
        ];
    }
}
