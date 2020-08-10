<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product_description;
use App\Stock_status;
use App\Product_special;
use App\Man;
use App\length_class_description;
use App\weight_class_description;
use App\product_image;
use App\Review;

class Product extends Model
{
    protected $table = "product";
    public $timestamps = false;
    protected $primaryKey = 'product_id';
    

    function desc()
    {
        return $this->hasMany(Product_description::class, 'product_id', 'product_id');
    }

    function stock()
    {
        return $this->hasMany(Stock_status::class, 'stock_status_id', 'stock_status_id')->orderBy('language_id' , 'DESC');
    }
    

    function special()
    {
        return $this->hasMany(Product_special::class, 'product_id', 'product_id');
    }
    function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    function man()
    {
        return $this->hasOne(Man::class, 'manufacturer_id', 'manufacturer_id');
    }
    function length()
    {
        return $this->hasMany(length_class_description::class, 'length_class_id', 'length_class_id');
    }
    function weight()
    {
        return $this->hasMany(weight_class_description::class, 'weight_class_id', 'weight_class_id');
    }
    function images()
    {
        return $this->hasMany(product_image::class, 'product_id', 'product_id')->orderBy('sort_order' ,'DESC');
    }
    
}
