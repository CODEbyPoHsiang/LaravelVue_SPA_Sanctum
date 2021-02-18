<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
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
 
   
 
        // throw ValidationException::withMessages([
        //     'email' => ['輸入的email信箱不正確'],
        // ]);

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
