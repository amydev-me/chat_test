<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\ConversationReply;
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

    public function sendConversation(Request $request)
    {

        $first_user = Conversation::where('user_id_one', Auth::user()->id)->where('user_id_two', $request->receiver_id)->first();
        $second_user = Conversation::where('user_id_one', $request->receiver_id)->where('user_id_two', Auth::user()->id)->first();
//        if($first_user||$second_user){
//            return 'yes';
//        }else{
//            return 'no';
//        }
//
//
//            return  response()->json(['first_user'=>$first_user,'second_user'=>$second_user]);
        if ($first_user || $second_user) {
            $reply = ConversationReply::create([
                'send_date' => Auth::user()->id,
                'message' => $request->message,
                'status' => 'Normal Chat',
                'user_id' => Auth::user()->id,
                'conversation_id' => $first_user ? $first_user->id : $second_user->id
            ]);
        } else {
            $conversation = Conversation::create([
                'user_id_one' => Auth::user()->id,
                'user_id_two' => $request->receiver_id,
                'room_title' => 'Normal Chat',
                'room_type' => 'chat'
            ]);
            $reply=ConversationReply::create([
                'send_date' => Auth::user()->id,
                'message' => $request->message,
                'status' => 'Normal Chat',
                'user_id' => Auth::user()->id,
                'conversation_id' => $conversation->id
            ]);
        }
        Event::fire(new MessageNotify($request));


        return response()->json(['conversation'=>$reply,'success'=>true],$this->successStatus);
    }

    public function loadConversations($id){


        $first_user = Conversation::with('conversation_replies')->where('user_id_one', Auth::user()->id)->where('user_id_two',$id)->first();
        $second_user = Conversation::with('conversation_replies')->where('user_id_one', $id)->where('user_id_two', Auth::user()->id)->first();
        return  response()->json($first_user?$first_user:$second_user);
    }

    public function getContacts(){
        $users = User::where('id','<>',Auth::user()->id)->orderBy('display_name')->get();
        return response()->json($users);
    }
}