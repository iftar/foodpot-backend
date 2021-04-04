<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;
use OauthClientSeeder;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegisterUser()
    {
        $password = $this->faker->password;
        $postData = [
            'email'             => $this->faker->email,
            'password'          => $password,
            'confirm'           => $password,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->firstName,
            'type'              => 'user',
        ];
        $response = $this->postJson('/api/register', $postData);

        $response
            ->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "data" => [ "user" => [
                    'email'             => $postData['email'],
                    'first_name'        => $postData['first_name'],
                    'last_name'         => $postData['last_name'],
                    'type'              => $postData['type'],
                    'status'            => 'approved',
                ]]]);
    }

    public function testRegisterThenLoginWithoutVerifying()
    {
        $this->seed(OauthClientSeeder::class);
        // register
        $password = $this->faker->password;
        $postData = [
            'email'             => $this->faker->email,
            'password'          => $password,
            'confirm'           => $password,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->firstName,
            'type'              => 'user',
        ];
        $response = $this->postJson('/api/register', $postData);

        $response
            ->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "data" => [ "user" => [
                    'email'             => $postData['email'],
                    'first_name'        => $postData['first_name'],
                    'last_name'         => $postData['last_name'],
                    'type'              => $postData['type'],
                    'status'            => 'approved',
                ]]]);

        // login
        $loginData = [
            "email" => $postData["email"],
            "password" => $postData["password"],
        ];
        $response = $this->postJson('/api/login', $loginData);
        if(User::class instanceof MustVerifyEmail) {
            $response
                ->assertStatus(401)
                ->assertJson([
                    "status" => "error",
                    "message" => "User has not verified email"
                ]);
        } else {
            $response
                ->assertStatus(200);
        }
    }

    public function testRegisterThenLoginAfterVerifying()
    {
        // creates oauth tokens
        $this->seed(OauthClientSeeder::class);

        // register
        $password = $this->faker->password;
        $postData = [
            'email'             => $this->faker->email,
            'password'          => $password,
            'confirm'           => $password,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->firstName,
            'type'              => 'user',
        ];
        $response = $this->postJson('/api/register', $postData);

        $response
            ->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "data" => [ "user" => [
                    'email'             => $postData['email'],
                    'first_name'        => $postData['first_name'],
                    'last_name'         => $postData['last_name'],
                    'type'              => $postData['type'],
                    'status'            => 'approved',
                ]]]);

        // user object
        $user = User::find($response["data"]["user"]["id"]);

        // verify
        $verified = $user->markEmailAsVerified();
        $this->assertTrue($verified);

        // Passport checks
        $this->assertTrue(Passport::personalAccessClient()->exists());

        // login
        $loginData = [
            "email" => $postData["email"],
            "password" => $postData["password"],
        ];
        $response = $this->postJson('/api/login', $loginData);

        $response
            ->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "data" => [
                    "user" => [
                        'id'                => $user->id,
                        'email'             => $user->email,
                        'first_name'        => $user->first_name,
                        'last_name'         => $user->last_name,
                        'type'              => $user->type,
                        'status'            => 'approved',
                    ],
                    // "token" => $user->token() // @HasnatH to implement
                ]
            ]);
    }
}
