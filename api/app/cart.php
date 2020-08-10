<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class cart extends Model
{
    protected $table = 'cart';
    public $timestamps = false;
    protected $primaryKey = 'cart_id';
 
    
    public function product () {
        return $this->hasOne(Product::class , 'product_id' , 'product_id')->with(['desc' , 'stock' , 'special']);

    }
}
