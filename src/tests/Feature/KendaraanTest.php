<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Kendaraan;

class KendaraanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public $token = null;
    public $user  = null;
    public $mobil = null;
    public $motor = null;

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

        $this->mobil = Kendaraan::create(["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "kapasitas" => 4 , "tipe" => "Sedan", "stok"=> 100]);
        $this->motor = Kendaraan::create(["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "suspensi" => "monoshock" , "transmisi" => "otomatis", "stok"=> 100]);
        $this->token = $response["token"];
        $this->user  = $user;
    }

    public function test_list()
    {
        $response = $this->get('/api/v1/kendaraan',['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertJsonStructure([
            "kendaraan"=>[],
        ]);
        $response->assertStatus(200);
    }

    public function test_store_mobil(){
        $response  = $this->post('/api/v1/kendaraan/store',["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "kapasitas" => 4 , "tipe" => "Sedan", "stok"=> 100],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $kendaraan = $response["kendaraan"];

        $this->assertDatabaseHas("kendaraan", ["_id"=>$kendaraan["_id"]]);

        //cleaning
        Kendaraan::where("_id", $kendaraan["_id"])->delete();
    }

    public function test_store_motor(){
        $response   = $this->post('/api/v1/kendaraan/store',["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "suspensi" => "monoshock" , "transmisi" => "otomatis", "stok"=> 100],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $kendaraan = $response["kendaraan"];

        $this->assertDatabaseHas("kendaraan", ["_id"=>$kendaraan["_id"]]);
        //cleaning
        Kendaraan::where("_id", $kendaraan["_id"])->delete();
    }

    public function test_stok_mobil(){
        $response = $this->get('/api/v1/kendaraan/stok?id='.$this->mobil["_id"],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertJsonStructure(["stok"]);
        $response->assertStatus(200);
    }

    public function test_stok_motor(){

        $response = $this->get('/api/v1/kendaraan/stok?id='.$this->motor["_id"],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertJsonStructure(["stok"]);
        $response->assertStatus(200);
    }

    // test + cleaning
    public function test_destroy_mobil(){
        $response = $this->delete('/api/v1/kendaraan/destroy',["id"=> $this->mobil["_id"]],['HTTP_Authorization' => 'Bearer' . $this->token]);
        
        $this->assertDatabaseMissing("kendaraan", ["_id"=>$this->mobil["_id"]]);
        $response->assertStatus(200);
    }

    // test + cleaning
    public function test_destroy_motor(){
        $response = $this->delete('/api/v1/kendaraan/destroy',["id"=> $this->motor["_id"]],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $this->assertDatabaseMissing("kendaraan", ["_id"=>$this->motor["_id"]]);
        $response->assertStatus(200);
    }

    // some how teardown is triggered upfront when test case is called
    // public function tearDown():void{
    //     parent::tearDown();

    //     //clean user
    //     User::where("_id", $this->user->id)->delete();
    // }
}
