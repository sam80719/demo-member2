<?php


namespace App\Services;


use App\Mail\MyTestMail;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;

use Facade\Ignition\Exceptions\ViewExceptionWithSolution;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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


    private function registerAndLoginDate(Request $request): array
    {
        $request['password'] = Hash::make(config('auth.password_hash'));
        $verifyToken = app::make(AuthService::class)->tokenEncode($request['email'], static::EXPIRED_DAY);
//        $test = app::make(AuthService::class)->tokenDecode($verifyToken);
//        var_dump($test);
//        exit;
        return [
            'email' => $request['email'],
            'password' => $request['password'],
            'verify_token' => $verifyToken,
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
            $formDate = $this->registerAndLoginDate($request);
            $repo = app::make(UserRepository::class);
//            $repo->create($formDate);

            // todo send_mail
            $this->sendMail($formDate['email'],$formDate['verify_token']);

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


    public function sendMail($mail,$token)
    {
        try {


            $link = sprintf('%s', route('api.member.verify') . '?token=' . $token);
            $text = "連結為： ".$link;

//            Mail::raw($text, function($msg) use ($mail){
//                $msg->to($mail)->subject('test');
//            });

            Mail::to($mail)->send(new MyTestMail($link));
            exit;

        } catch (\Exception $e) {
            throw $e;
        }

    }


    public function testMail(){}


}
