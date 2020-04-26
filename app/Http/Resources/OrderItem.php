<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItem extends JsonResource
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
            'product' => $this->product->name,
            'images' => ProductImage::collection($this->product->images),
            'amount' => $this->price,
            'quantity' => $this->quantity

        ];
    }
}
