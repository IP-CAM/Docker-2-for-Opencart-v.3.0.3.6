<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_description extends Model
{
    protected $table = "category_description";
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';
    protected $primaryKey = 'category_id';


    protected $hidden = [
    'category_id', 
    'description',
    'meta_title',
    'meta_description',
    'meta_keyword'
    ];
}
