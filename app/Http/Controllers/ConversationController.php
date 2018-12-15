<?php

namespace App\Http\Controllers;

use App\Conversation;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function store(Request $request){
        $token = (bin2hex(openssl_random_pseudo_bytes(24)));
        $request['creator_id']=Auth::user()->id;
        $request['channel_id']= JWT::urlsafeB64Encode($token);
        Conversation::create($request->all());
    }
}
