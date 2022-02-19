<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        $user       = new User();
        $user->name = "test";
        $user->email= "email@mail.com";
        $user->password = Hash::make("password");
        $user->save();

        $mobil1 = Kendaraan::create(["year"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "kapasitas" => 4 , "tipe" => "Sedan", "stok"=> 100]);
        $mobil2 = Kendaraan::create(["year"=> 2018, "warna" => "hitam", "harga" => 20000, "mesin" => "Supercharger", "kapasitas" => 4 , "tipe" => "Racing", "stok"=> 100]);

        $motor1 = Kendaraan::create(["year"=> 2018, "warna" => "hitam", "harga" => 20000, "mesin" => "150cc", "suspensi" => "monoshock" , "transmisi" => "matic", "stok"=> 100]);
        $motor2 = Kendaraan::create(["year"=> 2018, "warna" => "hitam", "harga" => 20000, "mesin" => "120cc", "suspensi" => "monoshock" , "transmisi" => "manual", "stok"=> 100]);

        Transaksi::create(["order"=>[$motor1->toArray(), $mobil1->toArray()], "pengiriman"=> "alamat pengiriman", "payment"=>"cash"]);
        Transaksi::create(["order"=>[$motor1->toArray(), $motor2->toArray()], "pengiriman"=> "alamat pengiriman", "payment"=>"cash"]);
        Transaksi::create(["order"=>[$mobil2->toArray()], "pengiriman"=> "alamat pengiriman", "payment"=>"cash"]);
    } 
}
