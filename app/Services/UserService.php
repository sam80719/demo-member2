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

//        $request['password'] = strip_tags($request['password']);
//        $request['password'] = Hash::make(strval($request['password']));
        $request['password'] = encrypt(strip_tags($request['password']));

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





    public function createUser(Request $request)
    {
        try {
            $formDate = $this->registerDate($request);
            $repo = app::make(UserRepository::class);
            $repo->create($formDate);

            // todo 未來使用queue，避免堵塞
//            $this->sendMail($formDate['email'], $formDate['verify_token']);

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
            return \response()->json(array("code" => Response::HTTP_BAD_REQUEST,
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
            $data = array(
                "code" => 200,
                "msg" => '登入成功'
            );



            return response(json_encode($data), Response::HTTP_OK);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'user is not valid') {
                return \response()->json(array("code" => Response::HTTP_BAD_REQUEST,
                    "msg" => $e->getMessage()
                ), Response::HTTP_UNAUTHORIZED);
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

//            if (Auth::check($request['password'], $userData->password)) {
//                var_dump('The passwords match..');
//            } else {
//                var_dump('The passwords is not match..');
//            }

            if ($request['password'] !== decrypt($userData->password)) throw new \Exception("wrong password");

        } catch (\Exception $e) {
            throw $e;
        }

    }


    public function list()
    {
        try {
            $repo = app::make(UserRepository::class);
            return $repo->list();
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
