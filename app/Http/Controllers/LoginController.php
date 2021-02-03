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

        // if (Auth::attempt($credentials)) {
        //     $data = Auth::user();
        //     return response()->json(['data'=>$data,'message' => 'Login successful'], 200);
        // }

        // throw ValidationException::withMessages([
        //     'email' => ['The provided credentials are incorrect'],
        // ]);

        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

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

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'], 200);
    }
}