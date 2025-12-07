<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status, // You might want to return label too
            'status_label' => OrderStatus::tryFrom($this->status)?->label() ?? 'Unknown',
            'payment_method' => $this->payment_method,
            'payment_method_label' => PaymentMethod::tryFrom($this->payment_method)?->label() ?? 'Unknown',
            'total_price' => $this->total_price,
            'shipping_cost' => $this->shipping_cost,
            'created_at' => $this->created_at->toDateTimeString(),
            'items' => $this->whenLoaded('items', function() {
                return $this->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => $item->pivot->quantity,
                        'unit_price' => $item->pivot->unit_price,
                        'total_price' => $item->pivot->total_price,
                    ];
                });
            }),
            'shipping_address' => $this->whenLoaded('shippingAddress'),
        ];
    }
}
