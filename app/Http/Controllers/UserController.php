<?php

namespace App\Http\Controllers;

use App\Events\MessageNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Lcobucci\JWT\Parser;

class UserController extends Controller
{

    public $successStatus = 200;


    public function login(Request $request)
    {
        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('Personal Access Token')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        $access_id = (new Parser())->parse($token)->getHeader('jti');

        if (Auth::user()->tokens) {
            $access_token = Auth::user()->tokens->find($access_id);
            $success =$access_token->revoke();
            return response()->json(['success' => $success], $this-> successStatus);
        }
        return response()->json(['error'=>'Failed'], 401);
    }

    public function getData(){
        return ['Apple','Android','Web'];
    }

    public function test(){



        event(new MessageNotify());
    }
}
