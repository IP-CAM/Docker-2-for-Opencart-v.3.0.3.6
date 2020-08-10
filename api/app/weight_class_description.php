<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class weight_class_description extends Model
{
    //weight_class_description
    protected $table = "weight_class_description";
    public $timestamps = false;
    protected $primaryKey = 'weight_class_id';

    protected $hidden = ['weight_class_id' , 'language_id'];
}
