<?php

namespace App\Http\Service;

use App\Http\Repository\KendaraanRepository;
use App\Http\Repository\TransaksiRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class PenjualanService {

    public function insertPenjualan($data){
        $kendaraan          = $data["kendaraan"];
        $jumlah             = $data["jumlah"];
        $alamat_pengiriman  = $data["alamat_pengiriman"];
        $metode_pembayaran  = $data["metode_pembayaran"];

        $order  = [];
        foreach($kendaraan as $key => $item){
            $kendaraan = ((new KendaraanRepository))->find($item);

            if(is_null($kendaraan)){
                // kendaraan not found
                continue;
            }

            if($kendaraan->stok - $jumlah[$key] > 0 ){
                $kendaraan->stok -= $jumlah[$key];
                // update stok
                if(!(new KendaraanRepository)->update($kendaraan)){
                    return "something went wrong!";
                }
            }
            array_push($order,["kendaraan"=>$kendaraan->toArray(), "jumlah"=>(int)$jumlah[$key]]);
        }

        // insert transaksi if order is not empty
        if(count($order) != 0){
            $transaction = (new TransaksiRepository)->store($order, $alamat_pengiriman, $metode_pembayaran);
            return $transaction;
        }else{
            return null;
        }
    }
}