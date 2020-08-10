<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Helper;


class BannerImage extends Model
{
    protected $table = "banner_image";
    protected $appends = array('imageurl');
    protected $hidden = ['banner_image_id' , 'banner_id' , 'link' , 'image' , 'sort_order'];
    public function getImageurlAttribute()
    {
        return Helper::product_thumb($this->image , true); 
    }

}
