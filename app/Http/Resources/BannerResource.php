<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'image' => url('storage/' . $this->image),
            'button_text' => $this->button_text,
            'link' => $this->link,
            'order' => $this->order,
            'type' => $this->type,
            'language_id' => $this->language_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
