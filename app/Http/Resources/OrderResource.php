<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user' => $this->user,
            'payment_method' => $this->paymentMethod,
            'online_payment' => $this->onlinePayment,
            'payment_token' => $this->paymentMethod?->key === 'card-payment' ? $this->paymentToken() : null,
            'comment' => $this->comment,
            'shipping_address' => $this->shipping_address,
            'shipping_type' => $this->shippingType,
            'shipping_location' => $this->shippingLocation,
            'discount' => $this->discount,
            'status' => $this->getStatusById($this->status_id),
            'total' => $this->total,
            'sub_total' => $this->sub_total,
            'items' => OrderItemResource::collection($this->orderItems),
            'services' => $this->orderServices,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
