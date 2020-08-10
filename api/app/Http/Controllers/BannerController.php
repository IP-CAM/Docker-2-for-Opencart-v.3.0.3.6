<?php

namespace App\Http\Controllers;

use App\BannerImage;

class BannerController extends Controller
{
    public function index()
    {

        $banners = BannerImage::where(['banner_id' => 7])->get();

        return ($banners->toArray());
    }
}
