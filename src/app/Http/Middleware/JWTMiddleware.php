<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;


class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            if(!JWTAuth::parseToken()->authenticate()){
                return response()->json([
                    'data'=>[],
                    'message' => "Anda belum mendaftar/sesi Anda telah habis, silahkan Login kembali!"
                ],401);
            }
        }catch(TokenExpiredException $e){
            return response()->json([
                'data'=>[],
                'message' => "Anda belum mendaftar/sesi Anda telah habis, silahkan Login kembali!"
            ],401);
        }
        catch (JWTException $e){
            return response()->json([
                'data'=>[],
                'message' => "Anda belum mendaftar/sesi Anda telah habis, silahkan Login kembali!"
            ],401);
        }
        catch(\Exception $e){
            return response()->json([
                'data'=>["error"=>$e->getMessage()],
                'message' => "Terjadi kesalahan, cobalah beberapa saat lagi..."
            ],500);
        }

        return $next($request);
    }
}
