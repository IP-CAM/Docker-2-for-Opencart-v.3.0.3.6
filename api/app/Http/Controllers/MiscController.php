<?php

namespace App\Http\Controllers;


use App\Http\Controllers\ProductController;
use App\Http\Controllers\BannerController;
use Illuminate\Http\Request;
use App\Country;
use App\Zone;

class MiscController extends Controller
{
    public function init () {
        $contries = Country::all();
        // $zones    = Zone::all();

        return $this->response()->array([
            'contries' => $contries,
            // 'zones'    => $zones,
        ]);
    }

    public function country_zones($id) {
        $zones = Zone::where([
            'country_id' => $id
        ])->get();

        return $zones;
    }

    public function home(){
        $producCtrl = new ProductController();
        $banner     = new BannerController();

        $special = $producCtrl->get_flash();
        $banner = $banner->index();
        $latest = $producCtrl->get_latest();


        return response([
            'latest' => $latest->original['latest'],
            'banner' => $banner,
            'special' => $special
        ] , 200);
    }
}
