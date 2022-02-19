<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $collection = 'transaksi';

    protected $fillable = ["order", "pengiriman", "payment"];
}
