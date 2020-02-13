<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $table = 'product_tag';

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
