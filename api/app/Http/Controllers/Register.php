<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Customer_activity;
use \App\Customer_ip;
use \App\Address;
use Carbon\Carbon;

use App\cart;


class Register extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rules = [
            'firstname' => ['required' , "min:4"],
            // 'lastname' => ['required' , "min:4"],
            // 'address_1' => ['required' , "min:4"],
            // 'city' => ['required' , "min:2"],
            // 'country_id' => ['required' , "numeric"],
            // 'zone_id' => ['required' , "numeric"],
            // 'password' => ['required', 'min:6'],
            'email' => ['required' , 'email' , 'unique:customer'],
            'telephone' => ['required' , 'min:8']
        ];

        $payload = app('request')->only('firstname', 'lastname',
         'address_1', 'zone_id', 'password', 'email', 'telephone');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('تحقق من الحقول المستخدمه.', $validator->errors());
        } else {
            $salt = substr(md5(uniqid(rand(), true)), 0, 9);
            $user = new User();
            $user->customer_group_id = 1;
            $user->store_id     = 0;
            $user->firstname    = $request->firstname;
            $user->lastname     = '.';
            $user->email        = $request->email;
            $user->telephone    = $request->telephone;
            $user->password     = sha1($salt . sha1($salt . sha1($request->password)));
            $user->salt         = $salt;
            $user->cart         = null;
            $user->wishlist     = null;
            $user->newsletter   = 1;
            // TODO :user address
            $user->status       = 1;
            // $user->approved     = 1;
            // $user->safe         = 0;
            // $user->fcm          = $request->fcm;
            $user->save();

            // log activity
            $user_activity = new Customer_activity();
            $user_activity->customer_id     = $user->customer_id;
            $user_activity->key             = 'register';
            $user_activity->data            = '{"customer_id":' . $user->customer_id . ',"name":"'. $user->firstname . ' ' . $user->lastname .'"}';
            $user_activity->ip              = $request->ip();
            $user_activity->save();


            // log user IP
            $user_ip = new Customer_ip();
            $user_ip->customer_id   = $user->customer_id;
            $user_ip->ip            = $user_activity->ip;
            $user_ip->save();


            // add user address
            $user_address = new Address();
            $user_address->customer_id  = $user->customer_id;
            $user_address->firstname    = $user->firstname;
            $user_address->lastname     = $user->lastname;
            $user_address->company      = '';
            $user_address->address_1    = 'EG';
            $user_address->address_2    = '';
            $user_address->city         = '';
            $user_address->country_id   = '63';
            $user_address->zone_id      = '1011';
            $user_address->save();

            // TODO:DONE add address to users
            $user->address_id = $user_address->address_id;
            $user->date_added =  Carbon::now();
            $user->save();
            

            $token = \JWTAuth::fromUser($user);
            $user->AuthKey = $token;
            
            
            
                     if ($request->has('uuid')){
                $cart = cart::where('session_id', '=', md5($request->uuid))->orderby('cart_id' , 'ASC')->get();
                foreach($cart as $carti){
                    $carti->customer_id = $user->customer_id;
                    $carti->save();
                }
            }
            
            
            return $this->response()->array($user);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
