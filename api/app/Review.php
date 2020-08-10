<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
class Review extends Model
{
    protected $table = "review";
    public $timestamps = false;
    protected $primaryKey = 'review_id';
    function product() {
        return $this->hasOne(Product::class , 'product_id' , 'product_id')->with(['desc' , 'stock' , 'special']);
    }
}