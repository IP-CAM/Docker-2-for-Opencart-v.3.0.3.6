<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article_list extends Model
{
    protected $table = 'article_list';

    public function list()
    {
        return $this->hasMany(Article_to_list::class, 'article_list_id','article_list_id')->with('article');
    }
}
