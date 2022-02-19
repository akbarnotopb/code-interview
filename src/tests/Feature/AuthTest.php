<?php

namespace Tests\Feature;

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
    
    public function test_user_jwt_login()
    {
        $user       = User::factory()->make([
            'password' => Hash::make("password"),
        ]);
        $user->save();

        $response = $this->post('/api/v1/login',[
            "email"     => $user->email,
            "password"  => "password",
        ]);

        if(isset($response["user"]) && isset($response["token"])){
            $response->assertStatus(200);

            //clean user
            User::where("_id", $user->_id)->delete();
        }else{
            throw new ErrorException("Creds supposed to be true");
        }
    }


    public function test_user_logout()
    {
        $user       = User::factory()->make([
            'password' => Hash::make("password"),
        ]);
        $user->save();

        $response = $this->post('/api/v1/login',[
            "email"     => $user->email,
            "password"  => "password",
        ]);
        
        $response  = $this->post("/api/v1/logout",[],['HTTP_Authorization' => 'Bearer' . $response["token"]]);

        $response->assertStatus(200);
        //clean user
        User::where("_id", $user->_id)->delete();
    }
}
