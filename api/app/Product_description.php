<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_description extends Model
{
    protected $table = "product_description";
    public $timestamps = false;
    protected $primaryKey = '';


    public function getDescriptionAttribute($value)
    {
       return html_entity_decode ($value);
    }
    
    public function getNameAttribute($value)
    {
       return html_entity_decode ($value);
    }
    
    protected $hidden = ['meta_title' , 'meta_description' , 'meta_keyword' , 'tag'  , 'language_id'];
    
}
