<?php
namespace App\Http\Helpers;

use App\Currency;
use App\Settings;
use Illuminate\Support\Facades\Log;

class Helper
{
    static function settings($key , $value=null)
    {
        $setting =  Settings::where('key', '=', $key)->get()->first();
        if ($setting) 
        {
            return $setting->value;
        }
        return $value;
    }

    static function getCurrency()
    {
        $currency = Helper::settings('config_currency');
        $currencies = Currency::where('code', '=', $currency)->get()->first();
        return $currencies;
    }

    static function product_thumb($image, $big = false)
    {
    //    Log::info('imageis ' . $image);
        if ($image) {
            $product_image = Helper::product_image($image);
            
            $dots = explode('.' , $product_image);
            
            $ext = $dots[count($dots) - 1];
            
            $dots[count($dots) - 1] = '';
            
            $pecies = [];
            $pecies[0] = substr($product_image, 0, - (strlen($ext) + 1));
            $pecies[1] =  '.' . $ext;
            $product_sub_image = [];

            
            if ($big == true){
                $product_sub_image[] =  ($pecies[0] . '-1140x380' . $pecies[1]);
                $product_sub_image[] = env('IMAGE_CATALOG') . $image;
                $product_sub_image[] =  ($pecies[0] . '-500x500' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-678x381' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-228x228' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-200x200' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-100x100' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-40x40' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-80x80' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-90x90' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-74x74' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-74x74' . $pecies[1]);
            }else{
                $product_sub_image[] =  ($pecies[0] . '-500x500' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-678x381' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-228x228' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-200x200' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-100x100' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-40x40' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-80x80' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-90x90' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-74x74' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-74x74' . $pecies[1]);
                $product_sub_image[] =  ($pecies[0] . '-1140x380' . $pecies[1]);
                $product_sub_image[] = env('IMAGE_CATALOG') . $image;
            }
            
            foreach ($product_sub_image as $key => $url) {
                // $ch = curl_init();

                // curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                // curl_setopt ($ch, CURLOPT_URL, ($url));
                // curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
                // // curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                // curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
                // curl_setopt($ch, CURLOPT_HEADER, true);
                // curl_setopt($ch, CURLOPT_NOBODY, true);
                // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
                // curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds

                // $content = curl_exec ($ch);
                // $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                // // Log::info('image:' . $url);
                // Log::info('content-type' . $contentType);
                

                    if (file_exists($url)){
                        $product_sub_image[$key] = str_replace(env('IMAGE_HOME_DIR') , env('IMAGE_URL') , $url);

                        // 

                    }else{
                    unset($product_sub_image[$key]);
                    }
            
            }

            //  Log::info($product_sub_image);
             $product_sub_image = array_values($product_sub_image);
            //  Log::info($product_sub_image);
            if (count($product_sub_image) > 0) {
                return $product_sub_image[0];
            } else {
                return env('DEFAULT_IMAGE');
            }
        } else {
            return false;
        }
    }
    

    static function product_image($image)
    {
 
            $product_image =  env('IMAGE_FOLDER') . $image;
            
            return $product_image;
    }
    
    static function product_over($products, $specialOnly = false)
    {
        $currencies = Helper::getCurrency();
        $ikey = 0;
        $response = [];
        foreach ($products as $key => $product) {
            if ($product) {
                $response[$ikey] = [
                "product_id"    =>  $product->product_id,
                "name"          =>  [
                'en' => $product->desc['0']->name,
                'ar' => $product->desc['1']->name,
                ],
                "model" => $product->model,
                "stock" =>  [
                'en' => $product->stock['1']->name,
                'ar' => $product->stock['0']->name,
                ],
                "price" =>  $currencies->symbol_left.' ' . @money_format('%.0n', $product->price) .' '. $currencies->symbol_right,
                "thumb_image" =>  Helper::product_thumb($product->image),
                ];
                
               
                if (count($product->special) > 0) {
                    $response[$ikey]['special']     =   $currencies->symbol_left .' ' . @money_format('%.0n', $product->special[0]->price) .' ' . $currencies->symbol_right;
                    $response[$ikey]['cut']         =   round(100 - ($product->special[0]->price * 100) / $product->price);
                    $response[$ikey]['date_end']    =   $product->special[0]->date_end;
                } else {
                    $response[$ikey]['special'] =  $response[$ikey]['date_end'] = $response[$ikey]['cut']= null;
                    
                }
            }
            if ($specialOnly) {
                if (count($product->special) == 0) {
                    unset($response[$ikey]);
                }
            }
            $ikey ++;
        }
            return array_values($response);
    }
}
