<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cart extends JsonResource
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
            'amount' => $this->amount,
            'quantity' => $this->quantity

        ];
    }
}
