<?php

namespace App\Http\Controllers;

use App\Article;
use App\Article_list;

class ArticleController extends Controller
{
    public function getList($id)
    {
        return Article_list::where('article_list_id', $id)->with('list')->get();
    }
    public function getArt()
    {
        $art = Article_list::get();
        return response($art, 200);
    }

    public function AllArt()
    {
        return Article::with('description')->get();
    }
    public function Recipe($id)
    {
        return Article::with('description')->where('article_id', $id)->firstOrFail();
    }
}
