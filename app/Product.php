<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = ['id'];

    protected $casts = [
        'images' => 'array',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getPicturesAttribute()
    {
        if ($this->images) {
            foreach (json_decode($this->images) as $picture) {
                $pictures[] = $picture;
            }
            return $pictures;
        }

        return null;
    }
}
