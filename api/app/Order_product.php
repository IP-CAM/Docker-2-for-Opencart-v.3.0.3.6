<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_product extends Model
{
    protected $table = 'order_product';
    public $timestamps = false;
    protected $primaryKey = 'order_product_id';
}
