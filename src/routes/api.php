<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PenjualanController;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("v1")->name("v1.")->group(function(){
    Route::post("login", [AuthController::class, "login"])->name("login");

    Route::middleware("jwt-auth")->group(function(){
        Route::post("logout", [AuthController::class , "logout"]);
        Route::post("refresh", [ AuthController::class, "refresh"]);
        
        Route::prefix("kendaraan")->name("kendaraan.")->group(function(){
            Route::get("/", [KendaraanController::class, "index"]);
            Route::post("/store", [KendaraanController::class, "store"]);
            Route::delete("/destroy", [KendaraanController::class ,"destroy"]);
            Route::get("/stok", [KendaraanController::class, "stok"]);
        });
        
        Route::prefix("penjualan")->group(function(){
            Route::post("/store", [PenjualanController::class, "store"]);
            Route::get("/report", [PenjualanController::class , "report"]);
        });
    });
});
