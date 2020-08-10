<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use JWTAuth;
use App\Post;
use App\User;
use App\Address;

class Profile extends Controller
{
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

    public function show(Request $request)
    {
        $address = Address::with(['country' , 'zone'])->where([
            'address_id' => $this->user->address_id,
        ])->get();

        return $this->response()->array([
            'address' => $address,
            'user' => $this->user->toArray(),
        ]);
    }


    public function update(Request $request)
    {

        $rules = [
            'firstname' => ['required' , "min:4"],
            'lastname' => ['required' , "min:4"],
            'telephone' => ['required','min:8']
        ];

        if ($request->email != $this->user->email) {
            $rules['email'] =  ['required' , 'email' , 'unique:customer'];
        }

        $payload = app('request')->only('firstname', 'lastname', 'email', 'telephone');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('تحقق من الحقول المستخدمه.', $validator->errors());
        } else {
            $user = User::findOrFail($this->user->customer_id);
            $user->firstname = $request['firstname'];
            $user->lastname = $request['lastname'];
            $user->email = $request['email'];
            $user->telephone = $request['telephone'];
            $user->save();
            return $this->response()->array($user);
        }
    }
}
