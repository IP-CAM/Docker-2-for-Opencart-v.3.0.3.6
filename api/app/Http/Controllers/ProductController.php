<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use Illuminate\Http\Request;

use App\Product_to_category;
use App\Category;
use App\Product;
use App\Product_special;
use App\Review;
use App\Product_related;
use DB;

class ProductController extends Controller
{
    public function get_latest()
    {

        $currencies = Helper::getCurrency();

        $products = Product::where('status','1')->with(['desc' , 'stock' , 'special'])->limit('50')->orderBy('product_id', 'desc')->get();
        if (count($products) > 0) {
            $response = Helper::product_over($products);
        }
        return $this->response()->array(['latest' => $response]);
    }

    public function get_special()
    {

        $currencies = Helper::getCurrency();

        $products = Product::where('status','1')->with(['desc' , 'stock' , 'special'])->orderBy('product_id', 'desc')->get();
        if (count($products) > 0) {
            $response = Helper::product_over($products , true);
        }else{
            $response = [];
        }
        return $this->response()->array(['special' => $response]);
    }
    public function get_review($id) {
        $reviews = Review::where(['product_id' => $id , 'status'=>'1'])->get()->all();
        return $reviews;
    }
    public function add_review(Request $request) {
        $rules = [
            'author' => ['required'  ],
            'product_id' => ['required' ],
            'text' => ['required' ],
            'rating' => ['required']
        ];
        $payload = app('request')->only('author', 'product_id',
         'text', 'rating');
        $validator = app('validator')->make($payload, $rules);
        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('تحقق من الحقول المستخدمه.', $validator->errors());
        } else {
            $review                 = new Review;
            if(!$request->customer_id) {
            $review->text           = $request->text;
            $review->rating         = $request->rating;
            $review->author         = $request->author;
            $review->product_id     = $request->product_id;
            }else {
            $review->text           = $request->text;
            $review->rating         = $request->rating;
            $review->author         = $request->author;
            $review->product_id     = $request->product_id;
            $review->customer_id    = $request->customer_id;
            }
            
            $review->save();
            return $this->response()->array(['review' => $review]);
        }
    }
    public function get_relate($id) {
        $related = Product_related::where('product_id' , $id)->get()->pluck('related_id');
 
        $prods = Product::with(['desc' , 'stock' , 'special'])->whereIn('product_id' , $related)->orderBy('product_id', 'desc')->get();
       
        $products  = Helper::product_over($prods , false);


        return $this->response()->array($products);
        

    }
    public function cat_products($id){


      
        $category = Category::with(['desc'])->where('category_id' , '=' , $id)->get()->first();
        $sons = Category::with(['desc' , 'sons'])->where('parent_id' , '=' , $id)->orderby('sort_order' , 'asc')->get();


        /*
        $products = Product_to_category::with(
            array('product' => function($query) 
            {
                if (intval(@$_GET['price_from']) > 0){
                    $query->where('product.price' , '>=' , $_GET['price_from']);
                }
                if (intval(@$_GET['price_to']) > 1 && 
                intval(@$_GET['price_to']) > intval($_GET['price_from'])){
                    $query->where('product.price' , '<=' , $_GET['price_to']);
                }
                
                 if (@$_GET['sort_by'] == "SORT_NEW"){
                    $query->orderBy('product.product_id', 'DESC');
                }else if (@$_GET['sort_by'] == "SORT_OLD"){
                    $query->orderBy('product.test', 'ASC');
                }else if (@$_GET['sort_by'] == "SORT_PRICE1"){
                    $query->orderBy('product.price', 'ASC');
                }else if (@$_GET['sort_by'] == "SORT_PRICE2"){
                    $query->orderBy('product.price', 'DESC');
                }else{
                    $query->orderBy('product.product_id', 'DESC');
                }
            }))->
        where('category_id' , '=' , $id)->
        get()->pluck('product'); */
        
        
        $products_array = Product_to_category::where('category_id' , '=' , $id)->
        get()->pluck('product_id');
        
        $products = Product::where('status','1')->with(['desc' , 'stock' , 'special'])->whereIn('product_id' , $products_array);
        
        
        if (intval(@$_GET['price_from']) > 0){
            $products->where('price' , '>=' , $_GET['price_from']);
        }
        if (intval(@$_GET['price_to']) > 1 && 
        intval(@$_GET['price_to']) > intval($_GET['price_from'])){
            $products->where('price' , '<=' , $_GET['price_to']);
        }
        
            if (@$_GET['sort_by'] == "SORT_NEW"){
            $products->orderBy('product_id', 'DESC');
        }else if (@$_GET['sort_by'] == "SORT_OLD"){
            $products->orderBy('product_id', 'ASC');
        }else if (@$_GET['sort_by'] == "SORT_PRICE1"){
            $products->orderBy('price', 'ASC');
        }else if (@$_GET['sort_by'] == "SORT_PRICE2"){
            $products->orderBy('price', 'DESC');
        }else if (@$_GET['sort_by'] == "SORT_ALPHA"){
            $products->orderBy('product_description.name', 'ASC');
        }else{
            $products->orderBy('product_id', 'DESC');
        }

        $products = $products->get();
        
        $response = [];
        if (count($products) > 0) {
            $response = Helper::product_over($products , false);
        }



        
        return $this->response()->array(['latest' => $response , 'sons' => $sons , 'cat' => $category]);
    }

    public function product($id) {


        $currencies = Helper::getCurrency();


        $product = Product::with(['desc' , 'stock' , 'special' , 'man' , 'length' , 'weight' , 'images'])->where([
            'product_id' => $id
            ])->findOrFail($id);
       
            if (  count($product->special) > 0  ) {
                $price = str_replace('$' ,'',$product->special[0]->price);
                $product->cut =  round(100 - ($price * 100) / $product->price);
            }
            else {
                $product->cut = 0;
            }

        $product->price = $currencies->symbol_left . @money_format('%i' , $product->price) . $currencies->symbol_right;
        $newOffset = count($product->images) + 1;
        $product->images[$newOffset] = new \stdClass();
         $product->images[$newOffset]->image = $product->image;
        $photos = [];
        if (count($product->images) > 0 ){
            foreach ($product->images as $key => $img) {
              
                    $photos[$key] =  Helper::product_thumb($img->image , true);
                
            }
        }
        $product->photos = array_values($photos);

        if (count($product->special) > 0 ){
            foreach ($product->special as $key => $special) {
                $product->special[$key]->price = $currencies->symbol_left .' '. @money_format('%.0n' , $special->price) .' '. $currencies->symbol_right;
            } 
        }
    
        // if (count($product->special) > 0){
            // $product->special = $currencies->symbol_left . $currencies->symbol_right;
        // }else{
            // $product->product->special = null;
        // }
        
        $product->review =  $this->get_review($id);
        $product->spects = $this->product_specs($id);

        return $this->response()->array($product->toArray());
    }

    public function get_flash(){
        $special = Product_special::where('date_start' , '<' , date('Y-m-d'))->where('date_end' , '>' , date('Y-m-d'))->orderBy('date_end' , 'desc')->get();



        $products = Product::where('status','1')->with(['desc' , 'stock' , 'special'])->
        wherein('product_id' , $special->pluck('product_id'))->
        orderBy('product_id', 'desc')->get();


        if (count($products) > 0) {
            $response = Helper::product_over($products);
        }


        return ([
            'count'=> $special->count() ,
            'ends_at' => @$special[0]->date_end,
            'products' => @$response
            ] );
    }


    public function product_specs($id){
        $spects = DB::select('
        SELECT oc_product.* , oc_product_attribute.* , oc_attribute_description.* FROM oc_product
        LEFT JOIN `oc_product_attribute` ON `oc_product_attribute`.`product_id` = `oc_product`.`product_id`
        LEFT JOIN `oc_attribute_description` ON `oc_attribute_description`.`attribute_id` = `oc_product_attribute`.`attribute_id`
        WHERE `oc_product`.`product_id` = '. $id);

        $spec_array = [];
        foreach($spects as $key => $spect){
           $spec_array[$spect->attribute_id][$spect->language_id] = [
               'key' => $spect->name,
               'value' => $spect->text,
           ];
        }

        $spec_parased_array = [];
        foreach($spec_array as $key => $item){
            $spec_parased_array[] = $item;
        }

        return $spec_parased_array;
    }
}
