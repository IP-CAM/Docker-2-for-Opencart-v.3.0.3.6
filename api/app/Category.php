<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Category_description;
use App\Http\Helpers\Helper;

class Category extends Model
{
    //data base
    protected $table = "category";
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';
    protected $primaryKey = 'category_id';
    protected $appends = array('imageurl');
    
    public function getImageurlAttribute()
    {
        return Helper::product_thumb($this->image , true); 
    }


    //fields
    protected $hidden = [
        'parent_id',
        'top',
        'column',
        'status',
        'sort_order',
        'date_added',
        'date_modified'
    ];


    // relations
    function sons () {
        return $this->hasMany(Category::class , 'parent_id' , $this->primaryKey)->with('desc');
    }
    function desc() {
        return $this->hasMany(Category_description::class , $this->primaryKey , $this->primaryKey)->orderBy('language_id');
    }


    // queries
    static function getall (){
        return Category::with(['desc' , 'sons'])->orderby('sort_order' , 'asc')->get();
    }
    static function Main(){
        return Category::where('parent_id', '=' , 0 )->with(['desc' , 'sons'])->orderby('sort_order' , 'asc')->get();
    }
}
