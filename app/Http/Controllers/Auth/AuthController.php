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
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'mailRegister', 'verifyMail','editMember']]);
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

    public function login(Request $request)
    {

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


//
        $credentials = $request->only('email', 'password');
//        $token = auth()->attempt($credentials);
//        $token = auth()->user();
//        echo '<pre>';
//        var_dump($credentials);
//        var_dump($token);
//        exit;
////        if (! $token = auth()->attempt($credentials)) {
////            return response()->json(['error' => 'Unauthorized'], 401);
////        }
//
//        return $this->respondWithToken($token);

        return app::make(UserService::class)->selectUser($request);


    }


    public function me()
    {
        return response()->json(auth()->user());
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
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
    public function editMember(Request $request, $id)
    {
        echo '<pre>';
        var_dump($id);
        var_dump($request);
        exit;
    }



    public function destroy(Request $request,$id)
    {
        echo '<pre>';
        var_dump($id);
        var_dump(3333);
        var_dump($request);
        exit;
    }
}
