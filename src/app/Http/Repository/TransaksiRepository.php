<?php

namespace App\Http\Repository;

use App\Models\Transaksi;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class TransaksiRepository {
    public function store($order, $alamat_pengiriman, $metode_pembayaran){
        return Transaksi::create(["order"=>$order, "pengiriman"=> $alamat_pengiriman, "payment"=>$metode_pembayaran]);
    }

    public function report(){
        $data = Transaksi::raw(function($collection){
            return $collection->aggregate([
                [
                    '$unwind' => '$order',
                ],
                [
                    '$unwind' => '$order.kendaraan'
                ],
                [
                    '$group'  => [
                        "_id"           => '$order.kendaraan._id',
                        "sold"          => [
                            '$sum'          => '$order.jumlah'
                        ],
                    ]
                ]
            ]);
        });
        return $data;
    }
}