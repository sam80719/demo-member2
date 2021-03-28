<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'mailRegister', 'verifyMail']]);
    }


    //  docker-compose exec laravel.test  curl --request POST -H "Content-Type: application/json" --data '{"email":"sam80719@gmail.com", "password":123456, "password_confirm":123456}' "http:/127.0.0.1/api/auth/register"

    public function mailRegister(Request $request)
    {
        app::make(AuthService::class)->handlePostHeader($request);

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \response()->json(array("code" => 400,
                "msg" => $validator->errors()->toJson()
            ));
        }
        return app::make(UserService::class)->createUser($request);
    }


    public function verifyMail(Request $request)
    {
        app::make(AuthService::class)->handleGetHeader($request);
        $token = strip_tags($request->input('token'));
        return app::make(UserService::class)->verifyMailToken($token);
    }



    // docker-compose exec laravel.test  curl --request POST -H "Content-Type: application/json" --data '{"email":"sam80719@gmail.com", "password":123456, "password_confirm":123456}' "http:/127.0.0.1/api/auth/login"

    public function login(Request $request){

        app::make(AuthService::class)->handlePostHeader($request);

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \response()->json(array("code" => 400,
                "msg" => $validator->errors()->toJson()
            ));
        }

        return app::make(UserService::class)->selectUser($request);


    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
