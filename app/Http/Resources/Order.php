<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'address' => new OrderAddress($this->address),
            'user' => new User($this->user),
            'items' => OrderItem::collection($this->items),
            'cost' => $this->amount,
            'status' => $this->status,
            'payment' => new OrderPayment($this->payment),
            'created_at' => $this->created_at
        ];
    }
}
