<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        if (Str::length($request->input('password')) < 6) {
            return response([
                "message" => " Password must be greater than 5 diget ",
            ]);
        }
        if (Str::length($request->input('employee_no')) < 4) {
            return response([
                "message" => " Employee number must be greater than 3 diget ",
            ]);
        }
        if (User::where("employee_no", $request->input('employee_no'))->get()->count() > 0) {
            return response([
                "message" => " The employee  is already exist ",
            ]);
        }
        try {
            return User::create([
                'employee_no' => $request->input('employee_no'),
                'employee_name' => $request->input('employee_name'),
                'role' => $request->input('role'),
                'password' => Hash::make($request->input('password')),
            ]);
        } catch (Exception $e) {
            return response([
                "message" => "there is error during create try again",
            ]);
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('employee_no', 'password'))) {
            return response([
                "message" => "Invalid credentials",
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24); // one day
        Redis::set('token', $token);
        return response([
            "message" => "Success",
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'Success',
        ])->withCookie($cookie);
    }

}
