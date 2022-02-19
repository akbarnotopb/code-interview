<?php

namespace App\Http\Controllers;

use App\Http\Service\KendaraanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    public function index(){
        return response()->json(["kendaraan"=>(new KendaraanService())->getAll()]);
    }

    public function stok(Request $request){
        $kendaraan = (new KendaraanService())->find($request->id);
        
        if(!is_null($kendaraan)){
            return response()->json(["stok"=>$kendaraan->stok, "message" => "berhasil"]);
        }else{
            return response()->json(["message"=>"Kendaraan tidak ditemukan", "stok"=>null],400);
        }
    }

    public function store(Request $request){

        $rules      = [
            "warna"             => "required",
            "tahun"             => "required|integer",
            "harga"             => "required|integer",
            "stok"              => "required|integer",
            "mesin"             => "required",
            "suspensi"          => "required_without_all:kapasitas, tipe",
            "transmisi"         => "required_without_all:kapasitas, tipe",
            "kapasitas"         => "required_without_all:suspensi, transmisi",
            "tipe"              => "required_without_all:suspensi, transmisi"
        ];
        $message    = [
            "required"          => ":attribute wajib diisi",
            "integer"           => ":attribute wajib berupa angka",
        ];

        $validator  = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return response()->json(["message"=> $validator->errors()->first(), "kendaraan" => null],400);
        }else if($request->suspensi && $request->kapasitas){
            return response()->json(["message"=> "Mobil atau motor? pilih salah satu!", "kendaraan" => null],400);
        }

        $request["stok"]        = (int)$request["stok"];

        $kendaraan  = (new KendaraanService)->store($request->except("_token"));

        if(!is_null($kendaraan)){
            return response()->json(["kendaraan"=>$kendaraan, "message" => "berhasil"]);
        }else{
            return response()->json(["message"=>"Terjadi kesalahan", "kendaraan"=>null],500);
        }
    }

    public function destroy(Request $request){
        $status  = (new KendaraanService)->destroy($request->id);

        if($status){
            return response()->json(["message" => "berhasil"]);
        }else{
            return response()->json(["message"=>"Kendaraan tidak ditemukan"],400);
        }
    }
}
