<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Service\AuthenticationService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login(Request $request){

        $rules      = [
            "email"             => "required|email",
            "password"          => "required"
        ];
        $message    = [
            "required"          => ":attribute wajib diisi",
            "email"             => ":attribute wajib berupa e-mail",
        ];

        $validator  = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return response()->json(["message"=> $validator->errors()->first(), "user" => null],400);
        }

        if($token = (new AuthenticationService())->login($request->only(["email","password"]))){
            return response()->json(["user"=>auth()->user(), "token" => $token]);
        }else{
            return response()->json(["message"=>"Email/Password salah"],401);
        }
    }

    public function logout(Request $request){
        if((new AuthenticationService)->logout(JWTAuth::getToken())){
            return response()->json(["message"=>"berhasil"]);
        }else{
            return response()->json(["message"=>"terjadi kesalahan"],500);
        }
    }

    public function refresh(Request $request){
        if($token = (new AuthenticationService())->refresh(JWTAuth::getToken())){
            return response()->json(["user"=>auth()->user(), "token" => $token]);
        }else{
            return response()->json(["message"=>"Email/Password salah"],401);
        }
    }
}
