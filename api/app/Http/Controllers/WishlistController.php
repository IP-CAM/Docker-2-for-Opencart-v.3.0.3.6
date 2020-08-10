<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Wishlist;
use App\Product;

class WishlistController extends Controller
{

    private $plusFields = ['product'];
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
        $products = Wishlist::with($this->plusFields)->where('customer_id', $this->user->customer_id)->get();


        $currencies = Helper::getCurrency();

        if (count($products) > 0) {
            $response = [];
            foreach ($products as $key => $product) {
                $response[$key] = [
                "product_id"    =>  $product->product_id,
                "name"          =>  [
                                        'en' =>$product->product->desc['0']->name,
                                        'ar' =>$product->product->desc['1']->name,
                                    ],
                "model" => $product->product->model,
                "stock" =>  [
                    'en' => $product->product->stock['1']->name,
                    'ar' => $product->product->stock['0']->name,
                ],
                "price" =>  $currencies->symbol_left . @money_format('%i', $product->product->price) . $currencies->symbol_right,
                "thumb_image" =>  Helper::product_thumb($product->product->image),
                ];
            }
            return $this->response()->array($response);
        } else {
            return $this->response()->array([]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product != null) {
            $wishlist = new Wishlist();
            $wishlist->product_id = $product->product_id;
            $wishlist->customer_id = $this->user->customer_id;
            $wishlist->date_added = date('Y-m-d H:i:s');
            $wishlist->save();


            $products = Wishlist::with(['product'])->where('customer_id', $this->user->customer_id)->get();
                            $favs = [];
            if (count($products) > 0) {
                foreach ($products as $key => $val) {
                    $favs[] = $val->product_id;
                }
            }
                            
            return $this->response()->array(['favs' => $favs]);
        } else {
            return $this->response->errorBadRequest('!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products = Wishlist::where([
              'customer_id' => $this->user->customer_id,
              'product_id'  => $id
        ])->get();
        if (count($products) == 1) {
             DB::table('customer_wishlist')->where([
              'customer_id' => $this->user->customer_id,
              'product_id'  => $id
             ])->delete();

            return $this->response()->array([]);
        } else {
        }
    }
}
