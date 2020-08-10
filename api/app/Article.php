<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Article_description;
class Article extends Model
{
    protected $table = 'article';

    static function getart(){
        return Article::with('artDesc')->get();
    }
    public function description()
    {
        return $this->hasMany(Article_description::class,'article_id','article_id');
    }

    public function list()
    {
        // return $this->hasMany(Article_to_list::class, 'article_list_id','article_list_id');
    }
}
