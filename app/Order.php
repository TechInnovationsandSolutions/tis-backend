<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['updated_at'];




    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(OrderAddress::class);
    }

    public function getProductsAttribute($products)
    {
        if ($products) {
            foreach (json_decode($products) as $product) {
                $p = Product::find($product->id);
                $prod[] = [
                    'name' => $p ? $p->name : '',
                    'amount' => $product->amount, 'quantity' => $product->quantity
                ];
            }
            return $prod;
        }

        return null;
    }
}
