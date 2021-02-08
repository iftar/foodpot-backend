<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\CharityUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CharityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGetCharityList()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, "api")->get('/api/charities');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'charities' => [] ]]);
    }

    public function testGetCharityProfile()
    {
        $user = factory(CharityUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->get('/api/charity');

        $response
            ->assertStatus(200)
            ->assertJson([
              'status' => 'success',
              'data'   => [ 'charity' => [
                  'id' => $user->charity()->id,
                  'name' => $user->charity()->name,
                  'registration_number' => $user->charity()->registration_number,
                  'max_delivery_capacity' => $user->charity()->max_delivery_capacity,
              ]]]);
    }

    public function testUpdateCharityName()
    {
        $user = factory(CharityUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/charity', [
          'name' => 'James foundation'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
              'status' => 'success',
              'data'   => [ 'charity' => [
                  'id' => $user->charity()->id,
                  'name' => 'James foundation',
                  'registration_number' => $user->charity()->registration_number,
                  'max_delivery_capacity' => $user->charity()->max_delivery_capacity,
              ]]]);
    }

    public function testUpdateCharityRegistrationNumber()
    {
        $user = factory(CharityUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/charity', [
          'registration_number' => '1321165464613'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
              'status' => 'success',
              'data'   => [ 'charity' => [
                  'id' => $user->charity()->id,
                  'name' => $user->charity()->name,
                  'registration_number' => '1321165464613',
                  'max_delivery_capacity' => $user->charity()->max_delivery_capacity,
              ]]]);
    }

    public function testUpdateCharityMaxDeliveryCapacity()
    {
        $user = factory(CharityUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/charity', [
          'max_delivery_capacity' => 132
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
              'status' => 'success',
              'data'   => [ 'charity' => [
                  'id' => $user->charity()->id,
                  'name' => $user->charity()->name,
                  'registration_number' => $user->charity()->registration_number,
                  'max_delivery_capacity' => 132,
              ]]]);
    }

    public function testUpdateCharityAll()
    {
        $user = factory(CharityUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/charity', [
          'name' => 'James foundation',
          'registration_number' => '1321165464613',
          'max_delivery_capacity' => 132,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
              'status' => 'success',
              'data'   => [ 'charity' => [
                  'id' => $user->charity()->id,
                  'name' => 'James foundation',
                  'registration_number' => '1321165464613',
                  'max_delivery_capacity' => 132,
              ]]]);
    }
    public function testRegisterCharityUser() {
        $password = $this->faker->password;
        $postData = [
            'email'             => $this->faker->email,
            'password'          => $password,
            'confirm'           => $password,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->firstName,
            "charity_name"      => $this->faker->company,
            'type'              => 'charity',
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
}
