<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowroomResource extends JsonResource
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
            'image' => url('storage/' . $this->image),
            'address' => $this->address,
            'phones' => $this->phones,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
