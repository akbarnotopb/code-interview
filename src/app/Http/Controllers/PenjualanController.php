<?php

namespace App\Http\Controllers;

use App\Http\Service\PenjualanService;
use App\Http\Service\ReportService;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function store(Request $request){
        $transaction = (new PenjualanService)->insertPenjualan($request->all());
        if(!is_null($transaction)){
            return response()->json(["transaksi"=>$transaction, "message" => "berhasil"]);
        }else{
            return response()->json(["transaksi"=>null, "message" => "Terjadi kesalahan, kendaraan tidak ditemukan  / stok tidak mencukupi"]);
        }
    }


    public function report(){
        $transactions_per_kendaraan = (new ReportService)->report();
        return response()->json(["report" => $transactions_per_kendaraan]);
    }
}
