<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Man extends Model
{
    // manufacturer
    protected $table = "manufacturer";
    public $timestamps = false;
    protected $primaryKey = 'manufacturer_id';

    protected $hidden = ['manufacturer_id' , 'image' , 'sort_order'];
}
