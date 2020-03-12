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
            'id' => $this->id,
            'name' => $this->name,
            'category' => new Category($this->category),
            'description' => $this->description,
            'excerpt' => $this->excerpts,
            'cost' => $this->cost,
            'reduced_cost' => ($this->cost) - ($this->discount / 100 * $this->cost),
            'discount' => $this->discount . '%',
            'ratings' => Rating::collection($this->ratings),
            'avg_rating' => count($this->ratings) > 0 ? $this->ratings->sum('rate') / count($this->ratings) : 0,
            'images' => ProductImage::collection($this->images),
            'tags' => Tag::collection($this->tags)
        ];
    }
}
