<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article_to_list extends Model
{

    protected $table = 'article_to_list';
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id','article_id');
    }
}
