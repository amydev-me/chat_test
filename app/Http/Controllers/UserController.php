<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageNotify;
use App\Message;
use App\Participant;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
class UserController extends Controller
{

    public $successStatus = 200;


    public function login(Request $request)
    {
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('Personal Access Token')->accessToken;
            return response()->json(['success' => $success, 'access_token' => $success['token'], 'user' => $user], $this->successStatus);
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

    public function register(Request $request)
    {
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
    }

    public function getConversations(){
        $user_id = Auth::user()->id;

         $conversations_id = Participant::where('user_id',Auth::user()->id)->get()->pluck('conversation_id');

         $conversations = Conversation::with('participants')
                             ->whereIn('id',$conversations_id)
                            ->get()->pluck('id');

         $conversations_list = Participant::whereIn('conversation_id',$conversations)->where('user_id','<>',$user_id)->get();

         return response()->json($conversations_list);
    }

    public function sendMessage(Request $request){
        $conversation = Conversation::where('id',$request->conversation_id)->first();
        if($conversation) {
            $request['sender_id'] = Auth::user()->id;
            Message::create($request->all());

            $request['channel_id']=$conversation->channel_id;

            Event::fire(new MessageNotify($request));
        }
    }

    public function test(){

    }
}