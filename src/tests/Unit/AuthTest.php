<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use ErrorException;
use Exception;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_user_jwt_wrong_login()
    {
        $user       = User::factory()->make([
            'password' => Hash::make("password"),
        ]);
        $user->save();

        $response = $this->post('/api/v1/login',[
            "email"     => $user->email,
            "password"  => "password123",
        ]);

        if(!isset($response["user"]) && !isset($response["token"])){
            $response->assertStatus(401);
        }else{
            throw new ErrorException("Creds supposed to be false");
        }
    }

    public function test_user_refresh_token()
    {
        $user       = User::factory()->make([
            'password' => Hash::make("password"),
        ]);
        $user->save();

        $response = $this->post('/api/v1/login',[
            "email"     => $user->email,
            "password"  => "password",
        ]);
        
        $old_token = $response["token"]; 
        $response  = $this->post("/api/v1/refresh",[],['HTTP_Authorization' => 'Bearer' . $response["token"]]);

        if(isset($response["user"]) && isset($response["token"]) && $old_token != $response["token"]){
            $response->assertStatus(200);
        }else{
            throw new ErrorException("Creds supposed to be true && token is updated");
        }
    }


    public function test_user_invalid_input()
    {
        $user       = User::factory()->make([
            'password' => Hash::make("password"),
        ]);
        $user->save();

        $response = $this->post('/api/v1/login',[
            "aaa"     => $user->email,
        ]);

        $response->assertStatus(400);
    }

}
