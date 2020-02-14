<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class Product extends JsonResource
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
            'name' => $this->name,
            'category' => $this->category->name,
            'description' => $this->description,
            'excerpt' => $this->excerpts,
            'cost' => $this->cost,
            'reduced_cost' => ($this->cost) - ($this->discount / 100 * $this->cost),
            'discount' => $this->discount . '%',
            'ratings' => Rating::collection($this->ratings),
            'images' => ProductImage::collection($this->images),
            //'tags' => Tag::collection($this->tags)
        ];
    }
}
