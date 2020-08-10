<?php
 
namespace App\Http\Controllers;

use App\Coupon;
use App\CouponHistory;
use App\OrderTotal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Helpers\Helper;
use App\Order;
use App\Address;
use App\cart;
use App\Order_product;

class OrderController extends Controller
{
    private $plusFields = ['country' , 'zone' , 'status' , 'products' , 'currency'];
    private $user;
    public function __construct()
    {
        $this->middleware('api.auth');

        try {
            if (! $this->user = \JWTAuth::parseToken()->authenticate()) {
                throw new UnauthorizedHttpException('عفوا تاكد من البريد و كلمه المرور.');
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
              return $this->response->errorUnauthorized('عفوا تاكد من البريد و كلمه المرور.');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::with($this->plusFields)->where('customer_id', $this->user->customer_id)->get();

        if (count($order) < 1) {
            return $this->response()->array([]);
        } else {
            $response = [];
            foreach ($order as $key => $order) {

                $response[] = [
                    'order_id'  => (int) $order->order_id,
                    'name'      => $order->firstname . ' ' . $order->lastname,
                    'date_added' => $order->date_added->format('m/d/Y'),
                    'products'   =>  count($order->products),
                    'total'      => $order->total,
                    'status'    => $order->status,
                ];
            }
            return $this->response()->array($response);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with($this->plusFields)->where([
            'customer_id'=> $this->user->customer_id,
            'order_id' => $id
        ])->get();
        if (count($order) < 1) {
            return $this->response()->array([]);
        } else {
            return $this->response()->array($order->toArray());
        }
    }
    public function store(Request $request)
    {
       if ($this->user->customer_id == 0){
            if ($request->has('uuid')){
            $start = ($request->uuid);
        }else{
            return response('UUID IS REQUIRED' , 500);
        }

        $cart = cart::with(['product'])->where('session_id', '=', $start)->get();
       }else{
           
        $cart = cart::with(['product'])->where('customer_id', '=', $this->user->customer_id)->get();
       }
        
        foreach($cart as $carti){
            $carti->customer_id = $this->user->customer_id;
            $carti->save();
        }
        


        if ($cart && count($cart) > 0) {
            $address = Address::findOrFail($request->address_id);
            
            $order   = new Order();
            $order->invoice_no = 0;
            $order->invoice_prefix = 'INV-2013-00';
            $order->store_id = 0;
            $order->store_name = Helper::settings('config_name');
            $order->store_url = 'http://ultraeg.com/';
            $order->customer_id = $this->user->customer_id;
            $order->customer_group_id = 1;
            $order->firstname = $this->user->firstname;
            $order->lastname = $this->user->lastname;
            $order->email = $this->user->email;
            $order->telephone = $this->user->telephone;
            $order->fax = '';
            $order->custom_field = '';
            $order->payment_firstname = $this->user->firstname;
            $order->payment_lastname = $this->user->lastname;
            $order->payment_company ='';
            $order->payment_address_1 = $address->address_1;
            $order->payment_address_2 = $address->address_2;
            $order->payment_city = $address->city;
            $order->payment_postcode = 0;
            $order->payment_country = $address->country->name;
            $order->payment_country_id =$address->country->country_id;
            $order->payment_zone = $address->zone->name;
            $order->payment_zone_id = $address->zone->zone_id;
            $order->payment_address_format = 0;
            $order->payment_custom_field = '[]';
            if ($request->has('payment')){
                 $order->payment_method = $request->payment;
            }else{
                 $order->payment_method = 'الدفع عند الاستلام';
            }
            $order->payment_code = 'cod';
            $order->shipping_firstname = $this->user->firstname;
            $order->shipping_lastname = $this->user->lastname;
            $order->shipping_company = 0;
            $order->shipping_address_1 = $address->address_1;
            $order->shipping_address_2 = $address->address_2;
            $order->shipping_city = $address->city;
            $order->shipping_postcode = 0;
            $order->shipping_country = $address->country->name;
            $order->shipping_country_id = $address->country->country_id;
            $order->shipping_zone = $address->zone->name;
            $order->shipping_zone_id = $address->zone->zone_id;
            $order->shipping_address_format = 0;
            $order->shipping_custom_field = '[]';
            if ($request->has('shipping')){
                if ($request->shipping == 'flat.flat'){
                    $order->shipping_method = 'Flat Shipping Rate';
                    $order->shipping_code = 'flat.flat';
                }else if ($request->shipping == 'free.free'){
                    $order->shipping_method = 'شحن مجاني';
                    $order->shipping_code = 'free.free';
                }else if ($request->shipping == 'pickup.pickup'){
                    $order->shipping_method = 'الاستلام من المتجر';
                    $order->shipping_code = 'pickup.pickup';
                }
            }
            $order->comment = $request->description;
            $order->total = 0;
            $order->order_status_id = 1;
            $order->affiliate_id = 0;
            $order->commission = 0;
            $order->marketing_id = 0;
            $order->tracking = 0;
            $order->language_id = 2;
            $order->currency_id = 4;
            $order->currency_code ='SAR';
            $order->currency_value = 1;
            $order->ip = $request->ip();
            $order->forwarded_ip = 0;
            $order->user_agent = 'API';
            $order->accept_language = 'en-US,en;q=0.8,ar;q=0.6';
            $order->date_added = new \DateTime();
            $order->date_modified = new \DateTime();
            $order->save();



            $total = $sub_total=  0;
            foreach ($cart as $key => $product) {
                $op = new Order_product();
                $op->order_id = $order->order_id;
                $op->product_id = $product->product_id;
                $op->model      = $product->product->model;
                $op->name       = $product->product->desc[0]->name;
                $op->quantity   = $product->quantity;
                
               
                if (count($product->product->special) > 0){
                  
                  if (strtotime($product->product->special[0]->date_end) <= 0 || strtotime($product->product->special[0]->date_end) >= time() && strtotime($product->product->special[0]->date_start) < time())
                  {
                            $op->price      = $product->product->special[0]->price;
                  }else{
                        $op->price      = $product->product->price;
                  }
                }else{
                      $op->price      = $product->product->price;
                }
                
                $op->total      =  $op->price * $product->quantity;
                $op->tax        = 0;
                $op->reward     = 0;
                $op->save();
                $product->delete();
                $total = $total + $op->total;
                $sub_total = $total;

                $order->comment = $product->option;
                $order->save();
            }



            $discount = 0;
            if ($request->has('promocode')){

                    $pcode = Coupon::where(['code' => $request->promocode])
                        ->where('date_start' , '<' , date('Y-m-d'))
                        ->where('date_end' , '>' , date('Y-m-d'))
                        ->where('status' , '=' , '1')
                        ->get()->first();
                    if ($pcode){
                        if ($pcode->type == "P"){
                            $discount = intval( ($total * $pcode->discount ) / 100);
                        }else if ($pcode->type == 'F'){
                            $discount = $pcode->discount;
                        }

                        $total = $total - $discount;
                        if ($total < 0){
                            $total = 0;
                        }

                        $cHistory = new CouponHistory();
                        $cHistory->coupon_id    = $pcode->coupon_id;
                        $cHistory->order_id     = $order->order_id;
                        $cHistory->customer_id  = $this->user->customer_id;
                        $cHistory->amount       = "-" . $discount;
                        $cHistory->date_added   = Carbon::now();
                        $cHistory->save();

                        $oTotal  = new OrderTotal();
                        $oTotal->order_id = $order->order_id;
                        $oTotal->code     = 'coupon';
                        $oTotal->title    = $pcode->name;
                        $oTotal->value    = "-" . $discount;
                        $oTotal->sort_order = 5;

                    }
            }

            $order->total = $total;
            $order->save();


            $oTotal  = new OrderTotal();
            $oTotal->order_id = $order->order_id;
            $oTotal->code     = 'sub_total';
            $oTotal->title    = 'الاجمالي';
            $oTotal->value    = $sub_total;
            $oTotal->sort_order = 1;
            $oTotal->save();

            $oTotal  = new OrderTotal();
            $oTotal->order_id = $order->order_id;
            $oTotal->code     = 'shipping';

            if ($request->has('shipping')){
                if ($request->shipping == 'flat.flat'){
                   $oTotal->title = 'Flat Shipping Rate';
                }else if ($request->shipping == 'free.free'){
                    $oTotal->title  = 'شحن مجاني';
                }else if ($request->shipping == 'pickup.pickup'){
                    $oTotal->title  = 'الاستلام من المتجر';
                }
            }
            $oTotal->value    = Helper::settings('flat_cost');
            $oTotal->sort_order = 3;
            $oTotal->save();



            $oTotal  = new OrderTotal();
            $oTotal->order_id = $order->order_id;
            $oTotal->code     = 'total';
            $oTotal->title    = 'الاجمالي النهائي';
            $oTotal->value    = $total;
            $oTotal->sort_order = 7;
            $oTotal->save();




            return $order;
        }else{
            return response(['msg' => 'لم تقم باضافه منتجات'] , 500);
        }
    }
}
