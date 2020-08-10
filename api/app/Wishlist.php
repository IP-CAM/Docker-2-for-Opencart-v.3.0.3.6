<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Wishlist extends Model
{
    protected $table = 'customer_wishlist';
    public $timestamps = false; 
    protected $primaryKey = null;
    public $incrementing = false;


    function product() {
        return $this->hasOne(Product::class , 'product_id' , 'product_id')->with(['desc' , 'stock' , 'special']);
    }
}
