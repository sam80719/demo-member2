<?php


namespace App\Repository;


use App\Exceptions\AuthExceptions\DuplicateException;
use App\Facades\Observers\UserBehaviorObserver;
use App\Facades\SyncService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(array $userData): User
    {
        try {
            DB::beginTransaction();

        }catch (\Exception $e){

        }

    }


}
