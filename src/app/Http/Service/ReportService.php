<?php

namespace App\Http\Service;

use App\Http\Repository\KendaraanRepository;
use App\Http\Repository\TransaksiRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportService {

    public function report(){
        $reports = (new TransaksiRepository())->report();

        foreach($reports as $report){
            $report->kendaraan = (new KendaraanRepository)->find($report->_id);
        }

        return $reports;
    }
}