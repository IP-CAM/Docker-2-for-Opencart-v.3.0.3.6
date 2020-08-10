<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
class Product_related extends Model
{
    protected $table = "product_related";
    public $timestamps = false;
    protected $primaryKey = null;
    function product() {
        return $this->hasMany(Product::class , 'product_id' , 'product_id')->with(['desc' , 'stock' , 'special']);
    }
}