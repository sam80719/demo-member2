<?php


namespace App\Services;


use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    public static $emailVerifyMapping = [
        'cognitoId' => 'id',
        'updatedAt' => 'uTime',
        'expireAt' => 'eTime'
    ];


    public function testUser(){
        echo 123;
    }

//
//    public function register(
//        string $email,
//        string $password,
//        string $name = '',
//        string $channel = ''
//    ): void {
//
//        $password = Hash::make(config('auth.password_hash'));
//        $repo = app(UserRepository::class);
//
////        if (Hash::check(config('auth.password_hash'), $request['password'])) {
////            var_dump('match');
////        } else {
////            var_dump('not_match');
////        }
//
//        $data = [
//            'email' => $email,
//            'password' => $password,
//        ];
//
//        $user = $repo->create($data);
//
//        $this->sendMail($user->identity_id, $email, $user->updated_at);
//    }
}
