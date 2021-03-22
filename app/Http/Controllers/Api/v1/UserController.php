<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{


    public function mailRegister(Request $request)
    {
        app::make(AuthService::class)->handleHeader($request);

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6'],
                'password_confirm' => ['required', 'same:password']
            ];
            $message = [
                "email.required" => "請填寫信箱",
                "email.email" => '信箱格式錯誤',
                "password.required" => "請填寫密碼",
                "password.min" => "至少填寫6碼",
                "password_confirm.required" => "請填入確認密碼",
                "password_confirm.same" => "確認密碼與密碼不同"
            ];
            $request->validate($rules, $message);
        } catch (\Exception $e) {
            return \response()->json(array("code" => 400,
                "msg" => $e->getMessage()
            ));
        }


        $request['password'] = Hash::make(config('auth.password_hash'));


//        $checkCode = $this->generateEmailActivationCode($request['email'], Carbon::now());
//        /** @var  UserService $UserService */
//        $UserService =App::make(UserService::class);
//


//
//        var_dump($checkCode);
//        exit;


        DB::beginTransaction();

        try {

            User::create([
                'email' => $request['email'],
                'password' => $request['password'],
            ]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return \response()->json(array("code" => 400,
                "msg" => $e->getMessage()
            ));
        }

        // todo send_mail
//        $this->sendMail($request['email'], Carbon::now());


        $data = array(
            "code" => 200,
            "msg" => '註冊成功'
        );
        return response(json_encode($data), Response::HTTP_CREATED);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
