<?php
namespace App\GraphQL\Mutation;

use GraphQL\GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;

class LogInMutation extends Mutation
{
    protected $attributes = [
        'name' => 'logIn'
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [
            'phone' => [
                'name' => 'phone',
                'type' => Type::nonNull(Type::string())

            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string())

            ],
        ];
    }
    public function rules()
    {
        return [
            'phone' => ['required'],
            'password' => ['required']
        ];
    }

    public function resolve($root, $args)
    {
        $credentials = [
            'phone' => $args['phone'],
            'password' => $args['password']
        ];
        $token ='';
        if(Auth::attempt(['phone' =>  $args['phone'], 'password' => $args['password']])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('Personal Access Token')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }
//        else{
//            return response()->json(['error'=>'Unauthorised'], 401);
//        }


//        if (!$token) {
//            throw new \Exception('Unauthorized!');
//        }

        return $args['phone'];
    }
}