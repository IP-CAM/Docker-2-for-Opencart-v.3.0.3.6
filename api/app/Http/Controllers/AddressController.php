<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $plusFields = ['country', 'zone'];
    private $user;
    public function __construct()
    {
        $this->middleware('api.auth');

        try {
            if (!$this->user = \JWTAuth::parseToken()->authenticate()) {
                throw new UnauthorizedHttpException('عفوا تاكد من البريد و كلمه المرور.');
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->response->errorUnauthorized('عفوا تاكد من البريد و كلمه المرور.');
        }
    }

    public function index()
    {
        $address = Address::with($this->plusFields)->where('customer_id', $this->user->customer_id)->get();
        if (count($address) < 1) {
            return $this->response()->array([]);
        } else {
            return $this->response()->array($address->toArray());
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
        $rules = [
            'address_1' => ['required', "min:4"],
            'city' => ['required', "min:2"],
            'country_id' => ['required', "numeric"],
            'zone_id' => ['required', "numeric"],
        ];

        $payload = app('request')->only('address_1', 'city', 'country_id', 'zone_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('تحقق من الحقول المستخدمه.', $validator->errors());
            die;
        }
        $user_address = new Address();
        $user_address->customer_id = $this->user->customer_id;
        $user_address->firstname = $this->user->firstname;
        $user_address->lastname = $this->user->lastname;
        $user_address->company = '';
        $user_address->address_1 = $request->address_1;
        $user_address->address_2 = '';
        $user_address->city = $request->city;
        $user_address->country_id = $request->country_id;
        $user_address->zone_id = $request->zone_id;
        $user_address->save();
        if ($user_address->address_id) {
            return response(['msg' => 'ok'], 200);
        }

        return response(['msg' => 'ok'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_address = Address::with($this->plusFields)->where([
            'address_id' => $id,
            'customer_id' => $this->user->customer_id,
        ])->get();

        if (count($user_address)) {
            return $this->response()->array($user_address->toArray());
        } else {
            return $this->response()->array([]);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'address_1' => ['required', "min:4"],
            'city' => ['required', "min:2"],
            'country_id' => ['required', "numeric"],
            'zone_id' => ['required', "numeric"],
        ];

        $payload = app('request')->only('address_1', 'city', 'country_id', 'zone_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('تحقق من الحقول المستخدمه.', $validator->errors());
            die;
        }
        $user_address = Address::findorfail($id);
        $user_address->customer_id = $this->user->customer_id;
        $user_address->firstname = $this->user->firstname;
        $user_address->lastname = $this->user->lastname;
        $user_address->company = '';
        $user_address->address_1 = $request->address_1;
        $user_address->address_2 = '';
        $user_address->city = $request->city;
        $user_address->country_id = $request->country_id;
        $user_address->zone_id = $request->zone_id;
        $user_address->save();
        if ($user_address->address_id) {
            return response(['msg' => 'ok'], 200);
        }

        return response(['msg' => 'ok'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_address = Address::with($this->plusFields)->where([
            'address_id' => $id,
            'customer_id' => $this->user->customer_id,
        ])->get()->first();

        $address = Address::where('customer_id', $this->user->customer_id)->get();

        if (count($user_address) == 1 && count($address) > 1) {

            // delete
            Address::destroy($user_address->address_id);

            // reset
            $this->user->address_id = $address->first()->address_id;
            $this->user->save();

            return $this->response->created();

        } else {
            return $this->response->errorNotFound();
        }
    }
}
