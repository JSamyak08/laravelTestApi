<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\User\AddMoneyRequest;
use App\Http\Requests\Api\User\BuyCookieRequest;


class UserController extends APIController
{
    public function register(RegisterRequest $request)
    {
        try {
            $password= Hash::make($request->password);
            $user = ([
                'name' => $request->name ?$request->name:'',
                'email' => $request->email?$request->email:'',
                'password' => $password,
            ]);

            $result = User::create($user);
            return $this->respondSuccess($result, "User register Successfully");
        } catch (Exception $e) {
            return $this->respondWithError($e);
        }
    }
    public function login(LoginRequest $request)
    {
        try {

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::user();
                $success['token'] =  $user->createToken(config('app.name'))->accessToken;
                $success['name'] =  $user->name;

                return $this->respondSuccess($success, "Login Successfull");
            }
            else{
                return $this->respondWithError('Invalid Credentials');
            }
        } catch (Exception $e) {
            return $this->respondWithError($e);
        }
    }
    public function addMoneyToWallet(AddMoneyRequest $request){
        try {
            $authUser = Auth::user();
            $amount = $authUser->wallet + $request->amount;
            $user =User::where('id',$authUser->id)->first();
            $user->update(['wallet'=>$amount]);
            $result['wallet']=$amount;
            return $this->respondSuccess($result, "Money Add Successfull");
        } catch (Exception $e) {
            return $this->respondWithError($e);
        }

    }
    public function buyCookie(BuyCookieRequest $request){
        try {
            $request= $request->all();
            $authUser = Auth::user();
            $userWalletAmount = $authUser->wallet;
            $user =User::where('id',$authUser->id)->first();
            if($request['cookie_qantity'] > $userWalletAmount){
                return $this->respondWithError("insufficient balance for buy number of ".$request['cookie_qantity']." cookie");
            }else{
                $amount = $userWalletAmount-$request['cookie_qantity'];
                $user->update(['wallet'=>$amount]);
                $data['wallet']=$amount;
                $data['cookie']=$request['cookie_qantity'];

                return $this->respondSuccess($data,"Cookies Buy Successfull");
            }

        } catch (Exception $e) {
            return $this->respondWithError($e);
        }

    }
}
