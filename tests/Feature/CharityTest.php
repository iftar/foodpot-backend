<?php

namespace Tests\Feature;

use App\Models\CollectionPoint;
use Carbon\Carbon;
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
        $max_daily_capacity = rand(50, 100);
        $cut_off_point = Carbon::parse("8pm")->toTimeString();
        $postData = [
            'email'             => $this->faker->email,
            'password'          => $password,
            'confirm'           => $password,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->firstName,
            "charity_name"      => $this->faker->company,
            'type'              => 'charity',
            'registration_number' => $this->faker->name,
            'contact_telephone'   => $this->faker->phoneNumber,
            'company_website'     => $this->faker->url,
            "address_line_1"     => $this->faker->streetName,
            "address_line_2"     => $this->faker->streetAddress,
            "city"               => $this->faker->city,
            "county"             => $this->faker->country,
            "cut_off_point"      => $cut_off_point,
            "post_code"          => $this->faker->postcode,
            "max_daily_capacity" => $max_daily_capacity,
        ];

        $response = $this->postJson('/api/register', $postData);
        $collection_point = CollectionPoint::find(1);

        $this->assertNotNull($collection_point);
        $this->assertTrue($collection_point->charity->contains("id", 1));
        $response
            ->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "data" => [
                    "user" => [
                        'email'             => $postData['email'],
                        'first_name'        => $postData['first_name'],
                        'last_name'         => $postData['last_name'],
                        'type'              => $postData['type'],
                        'status'            => 'approved',
                    ],
                    "collection_point" => [
                        "id"                 => 1,
                        "address_line_1"     => $postData['address_line_1'],
                        "address_line_2"     => $postData['address_line_2'],
                        "city"               => $postData['city'],
                        "county"             => $postData['county'],
                        "post_code"          => $postData['post_code'],
                        "max_daily_capacity" => $max_daily_capacity,
                        'cut_off_point'        => $cut_off_point,
                    ],
                    "charity" => [
                        'id'                   => 1,
                        'name'                 => $postData['charity_name'],
                        'registration_number'  => $postData['registration_number'],
                        'contact_telephone'    => $postData['contact_telephone'],
                        'company_website'      => $postData['company_website']
                    ]
                ]]);


    }
}
