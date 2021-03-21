<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{


    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $userData): User
    {
        try {
            DB::beginTransaction();

        }catch (\Exception $e){

        }

    }


}
