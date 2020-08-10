<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Product_to_category extends Model
{
    protected $table = "product_to_category";
    public $timestamps = false;
    protected $primaryKey = null;

    function product() {
        return $this->hasOne(Product::class , 'product_id' , 'product_id')->with(['desc' , 'stock' , 'special']);
    }
}
