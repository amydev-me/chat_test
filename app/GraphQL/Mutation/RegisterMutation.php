<?php
namespace App\GraphQL\Mutation;

use App\User;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;

class RegisterMutation extends Mutation
{
    protected $attributes = [
        'name' => 'register'
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
            'display_name' => [
                'name' => 'display_name',
                'type' => Type::nonNull(Type::string())

            ],
        ];
    }
    public function rules()
    {
        return [
            'phone' => ['required'],
            'password' => ['required'],
            'display_name'=>['required']
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::create([
            'phone' => $args['phone'],
            'display_name' => $args['display_name'],
            'password' => bcrypt($args['password']),
        ]);


       $token=null;


        if(Auth::attempt(['phone' =>  $user['phone'], 'password' => $user['password']])){
            $user = Auth::user();
            $token =  $user->createToken('Personal Access Token')-> accessToken;
        }

        if (!$token) {
            throw new \Exception('Unauthorized!');
        }

        return $token;
    }
}