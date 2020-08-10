<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use \App\User;
use \App\Wishlist;
use App\cart;

class Login extends Controller
{
    public function create(Request $request)
    {
        $suser = User::where('email', '=', $request['email'])->get()->first();

    
    
        if ($suser != null) {
            $salt = $suser->salt;
        } else {
            return $this->response->errorUnauthorized('عفوا تاكد من البريد و كلمه المرور.');
        }

        $credentials = [
            'email' => $request['email'],
            'password' => sha1($salt . sha1($salt . sha1($request['password']))),
            'approved' => 1,
        ];
        $user = User::where($credentials)->get()->first();
        
        try {
            if ($user != null) {
                if (Auth::loginUsingId($user->customer_id)) {
                    try {
                        if (! $token = \JWTAuth::fromUser( Auth::user() )) {
                            throw new UnauthorizedHttpException('عفوا تاكد من البريد و كلمه المرور.');
                        } else {
                            //check db
                            $user = Auth::user();
                            if ($request->has('fcm')){
                                $user->fcm = $request->fcm;
                                $user->save();
                            }
                            $user->AuthKey = $token;
                               $products = Wishlist::with(['product'])->where('customer_id', $user->customer_id)->get();
                            $favs = [];
                            if (count($products) > 0) {
                                foreach ($products as $key => $val) {
                                    $favs[] = $val->product_id;
                                }
                            }
                            
                             if ($request->has('uuid')){
                $cart = cart::where('session_id', '=', md5($request->uuid))->orderby('cart_id' , 'ASC')->get();
                foreach($cart as $carti){
                    $carti->customer_id = $user->customer_id;
                    $carti->save();
                }
            }
                            return $this->response()->array([
                            'user' => $user,
                            'favs' => $favs
                            ]);
                        }
                    } catch (JWTException $e) {
                        throw new UnauthorizedHttpException('عفوا تاكد من البريد و كلمه المرور.');
                    }
                } else {
                    throw new UnauthorizedHttpException('عفوا تاكد من البريد و كلمه المرور.');
                }
            } else {
                throw new UnauthorizedHttpException('عفوا تاكد من البريد و كلمه المرور.');
            }
        } catch (UnauthorizedHttpException $e) {
            return $this->response->errorUnauthorized('عفوا تاكد من البريد و كلمه المرور.');
        }
    }

    public function create_auth(Request $request)
    {
        // grab credentials from the request
        // grab credentials from the request
        try {
            if (! $user = \JWTAuth::parseToken()->authenticate()) {
                throw new UnauthorizedHttpException('Wrong Credentials');
            } else {
                $user->AuthKey =  $token = \JWTAuth::fromUser($user);

                if ($request->has('fcm')){
                    $user->fcm = $request->fcm;
                    $user->save();
                }
                
                $products = Wishlist::with(['product'])->where('customer_id', $user->customer_id)->get();
                $favs = [];
                if (count($products) > 0) {
                    foreach ($products as $key => $val) {
                        $favs[] = $val->product_id;
                    }
                }
            if ($request->has('uuid')){
                $cart = cart::where('session_id', '=', md5($request->uuid))->orderby('cart_id' , 'ASC')->get();
                foreach($cart as $carti){
                    $carti->customer_id = $user->customer_id;
                    $carti->save();
                }
            }
            
                return $this->response()->array([
                    'user' => $user,
                    'favs' => $favs
                ]);
            }
        } catch (UnauthorizedHttpException $e) {
             return $this->response->errorUnauthorized($e);
        }
    }

    function update()
    {
    }
}
