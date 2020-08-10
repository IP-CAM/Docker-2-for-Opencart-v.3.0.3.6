<?php

namespace App\Http\Controllers;

use App\cart;
use App\Product;
use App\Coupon;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use DB;

class CartController extends Controller
{
    private $user;
    public function __construct()
    {
    try {

	if (! $this->user = \JWTAuth::parseToken()->authenticate()) {
		return response()->json(['user not found'], 404);
	}

    } catch (\Exception $e) {
    
        $this->user = new \stdClass();
        $this->user->customer_id = 0;
    
    }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($code = null)
    {
        $start = md5($_GET['uuid']);
        if ($this->user->customer_id == 0){
            $cart = cart::with(['product'])->where('session_id', '=', $start)->orderby('cart_id' , 'ASC')->get()->toArray();
        }else{
            $cart = cart::with(['product'])->where('customer_id', '=', $this->user->customer_id)->orderby('cart_id' , 'ASC')->get()->toArray();
        }
        
        $currencies = Helper::getCurrency();
        $total= 0;
        if (count($cart) > 0) {
            $response = [];
            foreach ($cart as $key => $product) {
                $response[$key] = [
                "product_id"    =>  $product['product_id'],
                "quantity"      =>  $product['quantity'],
                "cart"          =>  $product['session_id'],
                "name"          =>  [
                                        'en' =>$product['product']['desc']['0']['name'],
                                        'ar' =>$product['product']['desc']['1']['name'],
                                    ],
                "model" => $product['product']['model'],
                "stock_status" => $product['product']['stock_status_id'],
                "stock" =>  [
                    'en' => $product['product']['stock']['1']['name'],
                    'ar' => $product['product']['stock']['0']['name'],
                ],
                
                "thumb_image" =>  Helper::product_thumb($product['product']['image']),
                "quantity" => $product['quantity']
                ];
                
                
                


               $response[$key]['price'] =  @money_format('%i', $product['product']['price']);
               $response[$key]['pprice_total'] = 0;
               $response[$key]['special'] =  $response[$key]['special_cut']  = null;


                if ($product['product']['quantity'] <= 0) {
                    $response[$key]["stock_status"] = $product['product']['stock_status_id'];
                } else {
                    $response[$key]["stock_status"] = 7;
                }
               
               if ($product['product']['special']){
                  
                  if (strtotime($product['product']['special'][0]['date_end']) <= 0 || strtotime($product['product']['special'][0]['date_end']) > time() && strtotime($product['product']['special'][0]['date_start']) <time())
                  {
                    $total = $total + ($product['product']['special'][0]['price'] * $product['quantity']);
                    $response[$key]['pprice_total'] = ($product['product']['special'][0]['price'] * $product['quantity']);
                    $response[$key]['special'] =  @money_format('%i', $product['product']['special'][0]['price']);
                    $response[$key]['special_cut'] = round(100 - ($product['product']['special'][0]['price'] * 100) / $product['product']['price']);
                }else{
                    $total = $total + ($product['product']['price'] * $product['quantity']); 
                    $response[$key]['pprice_total'] = ($product['product']['price'] * $product['quantity']);
                  }
                    
               }else{
                    $total = $total + ($product['product']['price'] * $product['quantity']);
                    $response[$key]['pprice_total'] = ($product['product']['price'] * $product['quantity']);
               }
               if ($code != null){
                    $pcode = Coupon::where(['code' => $code])
                        ->where('date_start' , '<' , date('Y-m-d'))
                        ->where('date_end' , '>' , date('Y-m-d'))
                        ->where('status' , '=' , '1')
                        ->get()->first();
               if ($pcode){
                  $check = DB::table('coupon_product')->where('coupon_id' , '=' , $pcode->coupon_id)->get();
                  if(count($check) > 0)
                  {
                      foreach($check as $proCode)
                      {
                          if($proCode->product_id == $response[$key]['product_id'])
                          {
                             if ($pcode->type == "P"){
                                 $discountPro = ($product['product']['price'] * $product['quantity']);
                                  $discount = intval( ($discountPro * $pcode->discount ) / 100);
                                  $total = $total - $discount;
            
                              }else if ($pcode->type == 'F'){
                                  $total = $total - $pcode->discount;
                                  if ($total > 0){
                                      $total = 0;
                                  }
                              }
                          }
                          else
                          {
                              
                              $discount = 0;
                              $total = $total - $discount;
                          }
                          
                      }
                  }
                 else{
                     $total = 20;
                     if ($pcode->type == "P"){
                          $discount = intval( ($total * $pcode->discount ) / 100);
    
    
                          $total = $total - $discount;
            
    
                      }else if ($pcode->type == 'F'){
                          $total = $total - $pcode->discount;
                          if ($total > 0){
                              $total = 0;
                          }
                      }
                 }
                  

                }
                }
            }
            
            return $this->response()->array([
            'cart' => $response ,
            'shipping' =>  Helper::settings('flat_cost',20) ,
            'sub_total' =>    (Helper::settings('flat_cost',20)  + $total),
            'total' => @money_format('%i', $total) 
            ]);
        } else {
            return $this->response()->array([]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $rules = [
            'product_id' => ['required' , "exists:product,product_id"],
        ];

        $payload = app('request')->only('product_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Please Fill All Fields', $validator->errors());
        } else {

            $start = md5($_GET['uuid']);

            if ($this->user->customer_id == 0){
                $exist_cart = cart::where('session_id', '=', $start)->get()->first();
            }else{
                $exist_cart = cart::where('customer_id', '=', $this->user->customer_id)->get()->first();
            }


            $productInfo = Product::findorfail($request->product_id);
            if ($productInfo->quantity === '0' &&  $productInfo->stock_status_id === '5'){
                   return response(['Error'=> 'not avaialable'] , 500);
            }
            
            // dd($exist_cart);
            if (($exist_cart) ) {
                $exist_cart_item = cart::where([
                    'session_id' => $exist_cart->session_id,
                    'product_id'  => $request->product_id
                ])->orWhere([
                    'customer_id' => $this->user->customer_id,
                    'product_id'  => $request->product_id
                ])->get()->first();
                //   dd($exist_cart_item);
                if ($exist_cart_item != null) {
                    $exist_cart_item->customer_id = $exist_cart->customer_id;
                    $exist_cart_item->session_id  = $exist_cart->session_id;
                    $exist_cart_item->product_id  = $request->product_id;
                    $exist_cart_item->recurring_id = 0;
                    if ($request->has('color')){
                    $exist_cart_item->option      = json_encode(["color" => $request->color]);
                    }else{
                    $exist_cart_item->option      = json_encode([]);
                    }
                    $exist_cart_item->quantity    = $exist_cart_item->quantity + 1;
                    $exist_cart_item->date_added  = new \DateTime();
                    $exist_cart_item->save();
                } else {
                    // dd($exist_cart->session_id);
                    $new_cart = new cart();
                    $new_cart->customer_id = $exist_cart->customer_id;
                    $new_cart->session_id  = $exist_cart->session_id;
                    $new_cart->product_id  = $request->product_id;
                    $new_cart->recurring_id = 0;
                    if ($request->has('color')){
                    $new_cart->option      = json_encode(["color" => $request->color]);
                   }else{
                    $new_cart->option      = json_encode([]);
                   }
                    $new_cart->quantity    = 1;
                    $new_cart->date_added  = new \DateTime();
                    $new_cart->save();
                }
            } else {
                $new_cart = new cart();
                if ($this->user->customer_id != 0){
                    $new_cart->customer_id = $this->user->customer_id;
                }else{
                    $new_cart->customer_id =  \App\User::all()[ \App\User::all()->count() - 1]->customer_id + 1 ;
                }
                $new_cart->session_id  = $start;
                $new_cart->product_id  = $request->product_id;
                $new_cart->recurring_id = 0;
                if ($request->has('color')){
                $new_cart->option      = json_encode(["color" => $request->color]);
               }else{
            $new_cart->option      = json_encode([]);
               }
                $new_cart->quantity    = 1;
                $new_cart->date_added  = new \DateTime();
                $new_cart->save();
            }
            return $this->index();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($cart)
    {
        // dd($cart);
        $start = md5($_GET['uuid']);

        if ($this->user->customer_id == 0){
            $exist_cart = cart::where('session_id', '=', $start)->get()->first();
        }else{
            $exist_cart = cart::where('customer_id', '=', $this->user->customer_id)->get()->first();
        }


            // dd($exist_cart);
        if (count($exist_cart) > 0) {
            $exist_cart_item = cart::where([
            'customer_id' => $exist_cart->customer_id,
            'product_id'  => $cart
            ])->get()->first();
            $newVal = $exist_cart_item->quantity - 1;
            if ($exist_cart_item != null && $newVal > 0) {
                $exist_cart_item->customer_id = $exist_cart->customer_id;
                $exist_cart_item->session_id  = $exist_cart->session_id;
                $exist_cart_item->product_id  = $cart;
                $exist_cart_item->recurring_id = 0;
                $exist_cart_item->option      = '[]';
                $exist_cart_item->quantity    = $newVal;
                $exist_cart_item->date_added  = new \DateTime();
                $exist_cart_item->save();
            } else {
                $exist_cart_item->delete();
            }
        }
            return $this->index();
    }
    public function remove($cart)
    {
        $start = md5($_GET['uuid']);

        
        if ($this->user->customer_id == 0){
            $exist_cart = cart::where('session_id', '=', $start)->get()->first();
        }else{
            $exist_cart = cart::where('customer_id', '=', $this->user->customer_id)->get()->first();
            $start = $this->user->customer_id;
        }



        if (count($exist_cart) > 0) {
            $exist_cart_item = cart::where([
                'product_id'  => $cart
            ])->get();
 
            foreach($exist_cart_item as $item){
                if ($item->quantity > 1){
                    $item->quantity = $item->quantity - 1;
                    $item->save();
                }else{
                    $item->delete();
                }
            }
        }
        return $this->index();
    }

    public function promocode($code){
            $copon = Coupon::where(['code' => $code])
                ->where('date_start' , '<' , date('Y-m-d'))
                ->where('date_end' , '>' , date('Y-m-d'))
                ->where('status' , '=' , '1')
                ->get()->first();

            if ($copon){
                return response($copon , 200);
            }else{
                return response(['error' => 'Not found'] , 404);
            }
    }
}
