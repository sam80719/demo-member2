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
//        $this->middleware('auth:api', ['except' => ['login', 'mailRegister', 'verifyMail','editMember']]);
    }


    //  docker-compose exec laravel.test  curl --request POST -H "Content-Type: application/json" --data '{"email":"sam80719@gmail.com", "password":"abs123456", "password_confirm":"abs123456"}' "http:/127.0.0.1/api/auth/register"
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



    // docker-compose exec laravel.test  curl --request POST -H "Content-Type: application/json" --data '{"email":"sam80719@gmail.com", "password":"abs123456"}' "http:/127.0.0.1/api/auth/login"

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



        return app::make(UserService::class)->list();
    }


    // docker-compose exec laravel.test  curl --request PUT -X "Content-Type: application/json" --data '{"email":"sam807129@gmail.com"}' "http:/127.0.0.1/api/auth/update/2"
    public function editMember(Request $request,$id)
    {


        echo '<pre>';
        var_dump($id);

        var_dump($request);
        exit;
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
