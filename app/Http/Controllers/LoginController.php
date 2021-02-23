<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Google2FA;
use Carbon\Carbon;



use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login_old(Request $request)
    {
        // $credentials = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $messages = [
            //驗證未通過的訊息提示
            'email.required' => '信箱為必填欄位',
            'password.required' => '密碼不得為空白',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            $response = [
            'success' => false,
            'data' => "Error",
            'message' => $errors[0],
        ];
            return response()->json($response, 202);
        }

        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

        if ($user->password =="") {
            $response = [
                'success' => false,
                'message' => '您輸入的帳號密碼不存在，請洽管理員處理',
              ];
            return response()->json($response, 202);
        }

        //檢查帳號密碼有無錯誤
        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
            'success' => false,
            'message' => '您輸入的帳號密碼有錯誤或不存在，請重新輸入',
          ];
            return response()->json($response, 202);
        }
        $token = $user->createToken('token')->plainTextToken;
        $user->save();

        $response = [
            // 'success' => true,
            'user' => $user,
            'email' =>$user->email,
            'token' => $token,
            // 'message' => '二次驗證登入成功',
          ];
        return response()->json($response, 200);
    }
    
    public function login(Request $request)
    {
        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

        //檢查帳號密碼有無錯誤
        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
            'success' => false,
            'message' => '您輸入的帳號密碼有錯誤或不存在，請重新輸入',
          ];
            return response()->json($response, 202);
        }

        // 刪除掉之前留存的login-token(舊)資料
        $user->tokens()->where('name', '=', 'login-token')->delete();
        // 如果登入者未設置二次驗證，則執行獲取google二次驗證key及掃QR_CODE
        if ($user["google2fa_enable"] == "") {
            //再產生新的key及QR碼
            $google2fa = app('pragmarx.google2fa');
            $user["google2fa_secret"] =  ($google2fa->generateSecretKey(32));
            $user->save();
            //生成QR code
            $QR_Image = $google2fa->getQRCodeInline(
            // config('app.name'),
                $user['email'],
                "TestMonitor",
                $user['google2fa_secret'],
            );
            //  dd($QR_Image);
            $response = [
            'success' => 'getcode',
            'google2fa_secret' => $user["google2fa_secret"],
            'email' => $user["email"],
            'QR_status' => false,
            'QR_code' => $QR_Image,
            'message' => '請掃描QR碼，並執行二次驗證操作',
            ];
            return response()->json($response, 200);
        } else {
            // login_time 用來記錄登入時間
            // $user->login_time = Carbon::now();
            // $user->save();
            $response = [
            'success' => 'toConfirmTwoFa',
            'google2fa_secret' => $user["google2fa_secret"],
            'email' => $user["email"],
            'QR_status' => true,
            'user' => $user,
            'message' => '帳號密碼登入成功，請執行二次驗證操作',
          ];
            return response()->json($response, 200);
        }
    }


    public function user($email)
    {
        $user = User::where('email', '=', $email)->first();
        return response($user);
    }

    public function remove_password($email)
    {
        return User::where('email', '=', $email)->update(["password"=>""]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
