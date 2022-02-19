<?php

namespace App\Http\Repository;

use App\Models\Kendaraan;
use Tymon\JWTAuth\Facades\JWTAuth;

class KendaraanRepository {

    public function find($_id){
        $kendaraan = Kendaraan::where("_id", $_id)->first();
        return $kendaraan;
    }
    
    public function fetchAll(){
        $kendaraan = Kendaraan::all();
        return $kendaraan;
    }

    public function update(Kendaraan $kendaraan){
        $_id        = $kendaraan->_id;
        $kendaraan  = $kendaraan->toArray();
        unset($kendaraan["_id"]);
        if(Kendaraan::where("_id", $_id)->update($kendaraan)){
            return true;
        }
        return false;
    }

    public function store($datas){
        if($kendaraan = Kendaraan::create($datas)){
            return $kendaraan;
        }
        return false;
    }

    public function destroy($_id){
        if(Kendaraan::where("_id", $_id)->delete()){
            return true;
        }else{
            return false;
        }
    }
}