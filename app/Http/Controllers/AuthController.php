<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegisterFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Tài khoản hoặc mật khẩu không đúng.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Không thể đăng nhập'], 500);
        }

        Cookie::queue('token', $token, 1440);

        return response()->json(['token' => $token]);
    }

    public function register(RegisterFormRequest $request)
    {   
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
            ]);
    
            $token = JWTAuth::fromUser($user);
            
            return response()->json([
                'user' => $user, 
                'token' => $token,
                'status' => 201,
            ]);
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'Đăng ký không thành công! Vui lòng thử lại.'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            JWTAuth::invalidate($token);

            Cookie::queue(Cookie::forget('token'));

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
