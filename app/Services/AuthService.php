<?php


namespace App\Services;


use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    protected $userRepo;




    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }



    public function handleHeader(Request $request)
    {
        if ($request->header()['content-type'][0] !== 'application/json' || empty($request->all()) || !is_array($request->all())) {
            return \response()->json(array("code" => 400,
                "msg" => "bad request"
            ));
        }
        return true;
    }
}
