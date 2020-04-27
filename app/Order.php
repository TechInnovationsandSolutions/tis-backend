<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
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

    public function payment()
    {
        return $this->belongsTo(OrderPayment::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

   
}
