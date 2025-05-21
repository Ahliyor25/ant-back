<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'logo' => url("storage/" . $this->logo),
            'slug' => $this->slug,
            "categories" => $this->categories()->with('services')->get(),
            "subcategories" => $this->subcategories()->with('services')->get(),
            "file" => $this->file ? url("storage/" . $this->file) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
