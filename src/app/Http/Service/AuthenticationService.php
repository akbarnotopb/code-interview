<?php

namespace App\Http\Service;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationService {
    
    public function login($creds){
        if($token = JWTAuth::attempt($creds)){
            return $token;
        }else{
            return false;
        }
    }

    public function logout($token){
        JWTAuth::invalidate($token);
        return true;
    }

    public function refresh($token){
        if($token = JWTAuth::refresh($token)){
            return $token;
        }else{
            return false;
        }
    }

}