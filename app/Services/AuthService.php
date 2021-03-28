<?php


namespace App\Services;


use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthService
{


    protected $userRepo;


    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    public function handlePostHeader(Request $request)
    {

        if ($request->header()['content-type'][0] !== 'application/json' ||
            empty($request->all()) ||
            !is_array($request->all()) ||
            $request->method() !== 'POST'
        ) {
            return \response()->json(array("code" => 400,
                "msg" => "bad request"
            ));
        }
        return true;
    }


    public function handleGetHeader(Request $request)
    {

        if (empty($request->all()) ||
            !is_array($request->all()) ||
            $request->method() !== 'GET'
        ) {
            return \response()->json(array("code" => 400,
                "msg" => "bad request"
            ));
        }
        return true;
    }

    /**
     *carbon memo
     *min –返回最小日期。
     *max – 返回最大日期。
     *eq – 判斷兩個日期是否相等。
     *gt – 判斷第一個日期是否比第二個日期大。
     *lt – 判斷第一個日期是否比第二個日期小。
     *gte – 判斷第一個日期是否大於等於第二個日期。
     *lte – 判斷第一個日期是否小於等於第二個日期。
     */

    public function tokenEncode(string $mail, int $day, string $route = 'default')
    {
        $member = strval($mail);
        $source = config('auth.api_source');
        $nowTime = Carbon::now()->addDays($day)->timestamp;
        $enMember = encrypt($member);
        $responseWord = $enMember . "," . $source . "," . $nowTime . "," . $route;
        return encrypt($responseWord);
    }


    public function tokenDecode(string $verify_token)
    {
        $verify_token = strval($verify_token);
        $deToken = decrypt($verify_token);
        $arrDeToken = explode(",", $deToken);
        $member = $arrDeToken[0];
        $source = $arrDeToken[1];
        $expiredTime = intval($arrDeToken[2]);
        $page = $arrDeToken[3]; // 目前沒用來控制
        $number = count($arrDeToken);


        // 無效的token
        if ($source !== config('auth.api_source')) return ["status" => false, "msg" => 'illegal token source'];
        if ($number < 3) return ["status" => false, "msg" => 'illegal token format'];


        if ($expiredTime) {
            if (Carbon::parse($expiredTime)->gt(Carbon::now())) {
                $mail = decrypt($member);
                return [
                    "email" => $mail,
                    "route" => $page
                ];
            }
            return ["status" => false, "msg" => 'token is expired'];
        }
        return ["status" => false, "msg" => 'illegal token format'];
    }


}
