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
            'product_id' => $this->product->id,
            'images' => ProductImage::collection($this->product->images),
            'amount' => $this->price,
            'quantity' => $this->quantity,
            'deleted_at' => $this->product->deleted_at

        ];
    }
}
