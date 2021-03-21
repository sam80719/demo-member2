<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
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

    public static $cryptSalt = 'heyhey';


    public function mailRegister(Request $request)
    {

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return \response()->json(array("code" => 400,
                "msg" => $validator->errors()->toJson()
            ));
        }

        app::make(UserService::class)->testUser();




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


    public function generateEmailActivationCode(string $email, string $updatedAt): string
    {

        $separator = ';';


        $expireAt = Carbon::now()->addDays(7)->timestamp;
        $memberData = [
            'email' => $email,
            'updatedAt' => crypt(
                Carbon::createFromFormat('Y-m-d H:i:s', $updatedAt)->timestamp,
                self::$cryptSalt
            ),
            'expireAt' => base64_encode($expireAt)
        ];

        $checkCode = sprintf(
            '%s$%s',
            implode($separator, array_map('base64_encode', array_keys($memberData))),
            implode($separator, array_values($memberData))
        );
        return $checkCode;
    }


    protected function sendMail(
        string $email,
        string $updatedAt
    ): void
    {
        $checkCode = $this->generateEmailActivationCode($email, $updatedAt);


//        $to_name = 'TO_NAME';
//        $to_email = 'TO_EMAIL_ADDRESS';
//        $data = array('name'=>"Sam Jose", "body" => "Test mail");
//        Mail::send('emails', $data, function($message) use ($to_name, $to_email) {
//            $message->to($to_email, $to_name)->subject('Artisans Web Testing Mail');
//            $message->from('FROM_EMAIL_ADDRESS','Artisans Web');
//
//        try {
//            $response = MemberAlwaysRight::notify(
//                [
//                    'category_id' => 94,
//                    'recipient' => ['emails' => [$email]],
//                    'email_subject' => '確認您的帳戶',
//                    'email_body' => View::make(
//                        'member.activate',
//                        [
//                            'link' => sprintf(
//                                '%s?%s',
//                                config('anue.member_service_domain'),
//                                http_build_query([
//                                    'url' => config('anue.member_active_url') . $checkCode,
//                                    'email' => $email,
//                                ])
//                            ),
//                        ]
//                    )->render(),
//                ]
//            );
//        } catch (\Exception $e) {
//            \Log::error($e);
//            throw new HttpResponseException(
//                FormatJsonResponse(
//                    'ERR_DEPENDENCY_SERVICE_ERROR',
//                    [],
//                    ['member_always_right' => $e->getMessage()]
//                )
//            );
//        }


//        if ($response['status_code'] !== 200) {
//            throw new HttpResponseException(
//                FormatJsonResponse(
//                    'ERR_DEPENDENCY_SERVICE_ERROR',
//                    [],
//                    ['member_always_right' => $response]
//                )
//            );
//        }


    }
}
