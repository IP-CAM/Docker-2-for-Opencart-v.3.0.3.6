<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\BannerImage;

class Banner extends Model
{
    protected $table = "banner";


    public function image(){
        return $this->hasOne(BannerImage::class , 'banner_id' , 'banner_id');
    }
}
