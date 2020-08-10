<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_special extends Model
{
    protected $table = "product_special";
    public $timestamps = false;
    protected $primaryKey = 'product_special_id';

    protected $hidden = ['product_special_id'  , 'product_id' , 'customer_group_id' , 'priority'];
}
