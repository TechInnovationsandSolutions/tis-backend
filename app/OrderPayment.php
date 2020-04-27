<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    use SoftDeletes;
     protected $guarded = ['id'];

     public function order()
     {
         return $this->hasOne(Order::class);
     }
}
