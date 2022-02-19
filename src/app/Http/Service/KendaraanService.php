<?php

namespace App\Http\Service;

use App\Http\Repository\KendaraanRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class KendaraanService {

    public function getAll(){
        return (new KendaraanRepository())->fetchAll();
    }

    public function find($_id){
        return (new KendaraanRepository())->find($_id);
    }

    public function store($datas){
        return (new KendaraanRepository)->store($datas);
    }

    public function destroy($_id){
        return (new KendaraanRepository)->destroy($_id);
    }
}