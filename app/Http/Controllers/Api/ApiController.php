<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => $user
        ]);
    }

    public function login(Request $request){
        $request->validate([
            "email" => "required|string|email",
            "password" => "required|string"
        ]);

        $user = User::where("email", $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken("mytoken")->accessToken;

            return response()->json([
                "status" => true,
                "message" => "Login successfully",
                "token" => $token,
                "data" => $user
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid email or password",
            "data" => []
        ]);
    }

    public function profile(){
        $userData = auth()->user();
        $userData->makeHidden(['password']);

        return response()->json([
            "status" => true,
            "message" => "Profile information",
            "data" => $userData
        ]);
    }

    public function logout(){
        $token = auth()->user()->token();
        $token->revoke();

        return response()->json([
            "status" => true,
            "message" => "User logged out successfully."
        ]);
    }
}
