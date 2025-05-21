<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "category_id" => $this->category_id,
            "name" => $this->name,
            "description" => $this->description,
            "slug" => $this->slug,
            "icon" => url("storage/" . $this->icon),
            "order" => $this->order,
            'services' => ServiceResource::collection($this->services),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
