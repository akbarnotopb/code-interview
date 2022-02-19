<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $collection = 'kendaraan';

    protected $fillable   = [
        "warna",
        "tahun",
        "harga",
        "mesin",
        "suspensi",
        "transmisi",
        "kapasitas",
        "tipe",
        "stok",
    ];
}
