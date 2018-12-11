<?php

namespace App\Http\Controllers;

use App\Events\MessageNotify;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Lcobucci\JWT\Parser;
use Validator;
class UserController extends Controller
{

    public $successStatus = 200;


    public function login(Request $request)
    {
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('Personal Access Token')->accessToken;
            return response()->json(['success' => $success,'access_token'=>$success['token'],'user'=>$user], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        $access_id = (new Parser())->parse($token)->getHeader('jti');

        if (Auth::user()->tokens) {
            $access_token = Auth::user()->tokens->find($access_id);
            $success = $access_token->revoke();
            return response()->json(['success' => $success], $this->successStatus);
        }
        return response()->json(['error' => 'Failed'], 401);
    }

    public function getData()
    {
        return ['Apple', 'Android', 'Web'];
    }


    public function register(Request $request){

        $validator = Validator::make($request->all(),
            [
                'phone' => 'required|unique:users',
                'display_name' => 'required',
                'password' => 'required|min:6'
            ]);
        $user = User::create([
            'phone' => $request->phone,
            'display_name' => $request->display_name,
            'password' => Hash::make($request->password),
        ]);
//
//
//        $token=null;
//        $user_info = null;
//
//        if(Auth::attempt(['phone' =>  $request['phone'], 'password' => $request['password']])){
//            $user_info = Auth::user();
//            $token =  $user->createToken('Personal Access Token')-> accessToken;
//        }else{
//            return response()->json(['success'=>false,'user'=>$user_info,'access_token'=>$token]);
//        }

        return response()->json(['success'=>true,'user'=>$user_info,'access_token'=>$token]);
    }



    public function test(Request $request)
    {

        Event::fire(new MessageNotify($request));
        return response()->json(['success' => true], 200);
    }
}
