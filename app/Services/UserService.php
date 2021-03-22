<?php


namespace App\Services;


use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
             $repo->create($formDate);


////


            // todo send_mail
//        $this->sendMail($request['email'], Carbon::now());


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


    public function generateEmailActivationCode(string $email, string $updatedAt): string
    {
        $a = encrypt(123);
        echo $a;
        echo "\n";
        echo decrypt($a);
        exit;

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

        var_dump($memberData);
        var_dump(implode($separator, array_map('base64_encode', array_keys($memberData))));
        var_dump(implode($separator, array_values($memberData)));


        $checkCode = sprintf(
            '%s$%s',
            implode($separator, array_map('base64_encode', array_keys($memberData))),
            implode($separator, array_values($memberData))
        );

        var_dump($checkCode);

        exit;
        return $checkCode;
    }



}
