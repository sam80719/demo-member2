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
        $test = app::make(AuthService::class)->tokenDecode($verifyToken);
        var_dump($test);
        exit;
        return [
            'email' => $request['email'],
            'password' => $request['password'],
            'verifyToken' => $verifyToken,
        ];
    }

    public static $emailVerifyMapping = [
        'cognitoId' => 'id',
        'updatedAt' => 'uTime',
        'expireAt' => 'eTime'
    ];


    public function createUser(Request $request)
    {

        $formDate = $this->registerAndLoginDate($request);
        $repo = app::make(UserRepository::class);
        $user = $repo->create($formDate);

//        $userDate['password'] = Hash::make(config('auth.password_hash'));


        exit;
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

//
//    public function register(
//        string $email,
//        string $password,
//        string $name = '',
//        string $channel = ''
//    ): void {
//
//        $password = Hash::make(config('auth.password_hash'));
//        $repo = app(UserRepository::class);
//
////        if (Hash::check(config('auth.password_hash'), $request['password'])) {
////            var_dump('match');
////        } else {
////            var_dump('not_match');
////        }
//
//        $data = [
//            'email' => $email,
//            'password' => $password,
//        ];
//
//        $user = $repo->create($data);
//
//        $this->sendMail($user->identity_id, $email, $user->updated_at);
//    }
}
