<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class length_class_description extends Model
{
    //length_class_description
    protected $table = "length_class_description";
    public $timestamps = false;
    protected $primaryKey = 'length_class_id';



    protected $hidden = ['length_class_id' , 'language_id'];
}
