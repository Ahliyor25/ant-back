<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistResource extends JsonResource
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
            'avatar' => $this->avatar ? url('storage/' . $this->avatar) : null,
            'category' => $this->specialistCategory,
            'slug' => $this->slug,
            'specialization' => $this->specialization,
            'experience' => $this->experience,
            'description' => $this->description,
            'images' => SpecialistImageResource::collection($this->images),
            'phone' => $this->phone,
            'instagram' => $this->instagram,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
