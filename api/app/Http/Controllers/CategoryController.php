<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Category;
class CategoryController extends Controller
{

    public function show() {
        $cats = Category::getall();
        return $this->response()->array(
            $cats->toArray()
        );
    }
    public function MainCats(){
        $main = Category::Main();
        return $this->response()->array(
            $main->toArray()
        );

    }
}
