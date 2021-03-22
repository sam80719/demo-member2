<?php


namespace App\Repositories;


use App\Models\User;
use http\Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isEmpty;

class UserRepository extends Repository
{


    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $userData)
    {
        // todo 想辦法用一條sql ，不需要撈兩次
        // todo 需要小心是否會sql injection
        DB::beginTransaction();
        try {
            $result = DB::table('users')
                ->where('email', '=', $userData['email'])
                ->whereNull('deleted_at')
                ->get()->toArray();

            if (!empty($result))  throw new \Exception("duplicate email");


            User::create([
                'email' => $userData['email'],
                'password' => $userData['password'],
                'verify_token' => $userData['verify_token'],
            ]);
            DB::commit();
            return 'add success';
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


}
