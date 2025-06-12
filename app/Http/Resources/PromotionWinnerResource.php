<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionWinnerResource extends JsonResource
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
                'promotion' => new PromotionResource($this->whenLoaded('promotion')),
                'promotion_draw' => new PromotionDrawResource($this->whenLoaded('promotionDraw')),
                'promotion_prize' => new PromotionPrizeResource($this->whenLoaded('promotionPrize')),
                'region' => new RegionResource($this->whenLoaded('region')),
                'full_name' => $this->full_name,
                'month' => $this->month,
                'prize_type' => $this->prize_type,
                'image' => $this->image,
                'created_at' => $this->created_at,
            ];
    }
}
