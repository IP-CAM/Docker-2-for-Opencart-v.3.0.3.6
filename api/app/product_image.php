<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_image extends Model
{
   //weight_class_description
    protected $table = "product_image";
    public $timestamps = false;
    protected $primaryKey = 'product_image_id';

    protected $hidden = ['product_image_id' , 'product_id' , 'sort_order'];
}
