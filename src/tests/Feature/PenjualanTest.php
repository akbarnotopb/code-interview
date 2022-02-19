<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Kendaraan;

class PenjualanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public $token = null;
    public $user  = null;

    public function setUp() :void{
        parent::setUp();
        $user       = User::factory()->make([
            'password' => Hash::make("password"),
        ]);
        $user->save();

        $response = $this->post('/api/v1/login',[
            "email"     => $user->email,
            "password"  => "password",
        ]);

        $this->token = $response["token"];
        $this->user  = $user;
    }

    public function test_report()
    {
        $response = $this->get('/api/v1/penjualan/report',['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertJsonStructure([
            "report"=>[],
        ]);

        $response->assertStatus(200);
    }

    public function test_insert_penjualan(){
        $mobil = Kendaraan::create(["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "kapasitas" => 4 , "tipe" => "Sedan", "stok"=> 100]);
        $motor = Kendaraan::create(["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "suspensi" => "monoshock" , "transmisi" => "otomatis", "stok"=> 100]);

        $response = $this->post('/api/v1/penjualan/store',[
            "kendaraan" =>[$mobil->_id, $motor->_id],
            "jumlah"    =>[1,1],
            "alamat_pengiriman" => "alamat",
            "metode_pembayaran" => "payment",
        ],['HTTP_Authorization' => 'Bearer' . $this->token]);
        
        $response->assertJsonStructure(["transaksi", "message"]);
        $response->assertStatus(200);

        $this->assertDatabaseHas("transaksi",["_id"=>$response["transaksi"]["_id"]]);
        $this->assertDatabaseHas("kendaraan",["_id"=>$mobil->_id,"stok"=>100-1]); // cek stok mobil
        $this->assertDatabaseHas("kendaraan",["_id"=>$motor->_id,"stok"=>100-1]); // cek stok motor

    }

    // some how teardown is triggered upfront when test case is called
    // public function tearDown():void{
    //     parent::tearDown();

    //     //clean user
    //     User::where("_id", $this->user->id)->delete();
    // }
}
