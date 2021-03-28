<?php


namespace App\Services;


use App\Mail\MyTestMail;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;


class UserService
{
    protected $userRepo;
    public static $cryptSalt = 'heyhey';
    public const EXPIRED_DAY = 7;


    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    private function registerDate(Request $request): array
    {

        $request['password'] = strip_tags($request['password']);
        $request['password'] = Hash::make($request['password']);
        $verifyToken = app::make(AuthService::class)->tokenEncode($request['email'], static::EXPIRED_DAY);

        return [
            'email' => strip_tags($request['email']),
            'password' => $request['password'],
            'verify_token' => $verifyToken,
        ];
    }


    private function loginDate(Request $request): array
    {
        return [
            'email' => strip_tags($request['email']),
            'password' => strip_tags($request['password']),

        ];
    }


    public static $emailVerifyMapping = [
        'cognitoId' => 'id',
        'updatedAt' => 'uTime',
        'expireAt' => 'eTime'
    ];


    public function createUser(Request $request)
    {
        try {
            $formDate = $this->registerDate($request);
            $repo = app::make(UserRepository::class);
            $repo->create($formDate);

            // todo 未來使用queue，避免堵塞
            $this->sendMail($formDate['email'], $formDate['verify_token']);

            $data = array(
                "code" => 200,
                "msg" => '註冊成功'
            );
            return response(json_encode($data), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return \response()->json(array("code" => 400,
                "msg" => $e->getMessage()
            ));
        }
    }


    public function verifyMailToken($token)
    {
        try {
            $formData = app::make(AuthService::class)->tokenDecode($token);
            $repo = app::make(UserRepository::class);
            $repo->verifyByMail($formData, $token);

            $data = array(
                "code" => 200,
                "msg" => '驗證成功'
            );
            return response(json_encode($data), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return \response()->json(array("code" => 400,
                "msg" => $e->getMessage()
            ));
        }
    }


    public function sendMail($mail, $token)
    {
        try {
            $link = sprintf('%s', route('api.member.verify') . '?token=' . $token);
            Mail::to($mail)->send(new MyTestMail($link));
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function selectUser(Request $request)
    {
        try {

            $formDate = $this->loginDate($request);


            $this->checkUser($formDate);
//            $repo = app::make(UserRepository::class);


        } catch (\Exception $e) {
            if ($e->getMessage() === 'user is not valid') {
                return \response()->json(array("code" => 400,
                    "msg" => $e->getMessage()
                ), 401);
            }

            return \response()->json(array("code" => 400,
                "msg" => $e->getMessage()
            ));
        }

    }


    public function checkUser($request)
    {
        try {
            $repo = app::make(UserRepository::class);

            $userData = $repo->storeByEmail($request);


            if (empty($userData->email_verified_at)) throw new \Exception("user is not valid");


            echo '<pre>';
            var_dump($request['password']);
            var_dump($userData->password);
            exit;

            if (Auth::check($request['password'], $userData->password)) {
                var_dump(111);
            } else {
                var_dump(222);
            }
            exit;
            echo '<pre>';
            var_dump($request);
            var_dump($userData);
            exit;


        } catch (\Exception $e) {
            throw $e;
        }

    }


    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


}
