<?php

namespace Tests\Unit;

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

    public function test_list_without_token()
    {
        $response = $this->get('/api/v1/kendaraan');

        $response->assertStatus(401);
    }

    public function test_store_mobil_without_token(){
        $response  = $this->post('/api/v1/kendaraan/store',["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "kapasitas" => 4 , "tipe" => "Sedan", "stok"=> 100]);

        $response->assertStatus(401);
    }

    public function test_store_mobil_uncompleted(){
        $response  = $this->post('/api/v1/kendaraan/store',[ "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "kapasitas" => 4 , "tipe" => "Sedan", "stok"=> 100],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertStatus(400);
    }

    public function test_store_motor_without_token(){
        $response   = $this->post('/api/v1/kendaraan/store',["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "suspensi" => "monoshock" , "transmisi" => "otomatis", "stok"=> 100]);

        $response->assertStatus(401);
    }

    public function test_store_motor_uncompleted(){
        $response   = $this->post('/api/v1/kendaraan/store',["tahun"=> 1998, "harga" => 10000, "mesin" => "V8-Engine", "suspensi" => "monoshock" , "transmisi" => "otomatis", "stok"=> 100],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertStatus(400);
    }

    public function test_store_motor_mixed(){
        $response   = $this->post('/api/v1/kendaraan/store',["tahun"=> 1998, "warna" => "hitam", "harga" => 10000, "mesin" => "V8-Engine", "suspensi" => "monoshock" , "transmisi" => "otomatis", "kapasitas"=>4 , "tipe"=>"sedan", "stok"=> 100],['HTTP_Authorization' => 'Bearer' . $this->token]);
        $response->assertStatus(400);
    }


    public function test_stok_mobil_without_token(){
        $response = $this->get('/api/v1/kendaraan/stok?id='.$this->mobil["_id"]);

        $response->assertStatus(401);
    }

    public function test_stok_without_parameter(){
        $response = $this->get('/api/v1/kendaraan/stok?id=',['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertStatus(400);
    }

    public function test_stok_motor_without_token(){

        $response = $this->get('/api/v1/kendaraan/stok?id='.$this->motor["_id"]);

        $response->assertStatus(401);
    }

    public function test_destroy_mobil_without_token(){
        $response = $this->delete('/api/v1/kendaraan/destroy',["id"=> $this->mobil["_id"]]);

        $response->assertStatus(401);
    }

    // test + cleaning
    public function test_destroy_mobil_without_parameter(){
        $response = $this->delete('/api/v1/kendaraan/destroy',[],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertStatus(400);

        Kendaraan::where("_id", $this->mobil["_id"])->delete();
    }

    public function test_destroy_motor_without_token(){
        $response = $this->delete('/api/v1/kendaraan/destroy',["id"=> $this->motor["_id"]]);

        $response->assertStatus(401);
    }

    // test + cleaning
    public function test_destroy_motor_without_parameter(){
        $response = $this->delete('/api/v1/kendaraan/destroy',[],['HTTP_Authorization' => 'Bearer' . $this->token]);

        $response->assertStatus(400);

        Kendaraan::where("_id", $this->motor["_id"])->delete();
    }

    // some how teardown is triggered upfront when test case is called
    // public function tearDown():void{
    //     parent::tearDown();

    //     //clean user
    //     User::where("_id", $this->user->id)->delete();
    // }
}
