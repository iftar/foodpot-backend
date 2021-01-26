<?php

namespace Tests\Feature;

use App\Models\CollectionPoint;
use App\Models\CollectionPointUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectionPointTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCollectionPointList()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, "api")->get('/api/collection-points');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_points' => [] ]]);
    }

    public function testGetCollectionPoint()
    {
        factory(CollectionPoint::class)->create();
        $collectionPoint = CollectionPoint::find(1)->toArray();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, "api")->get('/api/collection-points/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_point' => $collectionPoint ]]);
    }

    public function testGetCollectionPointProfile()
    {
        $user = factory(CollectionPointUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->get('/api/collection-point');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_point' => [
                    'id' => $user->collectionPoint()->id,
                    'name' => $user->collectionPoint()->name,
                    'address_line_1' => $user->collectionPoint()->address_line_1,
                    'address_line_2' => $user->collectionPoint()->address_line_2,
                    'city' => $user->collectionPoint()->city,
                    'county' => $user->collectionPoint()->county,
                    'post_code' => $user->collectionPoint()->post_code,
                    'max_daily_capacity' => $user->collectionPoint()->max_daily_capacity,
                ]]]);
    }

    public function testUpdateCollectionPointName()
    {
        $user = factory(CollectionPointUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/collection-point', [
          'name' => 'ELM'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_point' => [
                    'id' => $user->collectionPoint()->id,
                    'name' => 'ELM',
                    'address_line_1' => $user->collectionPoint()->address_line_1,
                    'address_line_2' => $user->collectionPoint()->address_line_2,
                    'city' => $user->collectionPoint()->city,
                    'county' => $user->collectionPoint()->county,
                    'post_code' => $user->collectionPoint()->post_code,
                    'max_daily_capacity' => $user->collectionPoint()->max_daily_capacity,
                ]]]);
    }

    public function testUpdateCollectionPointAddress()
    {
        $user = factory(CollectionPointUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/collection-point', [
            'address_line_1'    => 'Fake house',
            'address_line_2'    => '123 Fake Street',
            'city'              => 'Fake city',
            'county'            => 'Fake county',
            'post_code'         => 'F4 K3E',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_point' => [
                    'id' => $user->collectionPoint()->id,
                    'name' => $user->collectionPoint()->name,
                    'address_line_1'    => 'Fake house',
                    'address_line_2'    => '123 Fake Street',
                    'city'              => 'Fake city',
                    'county'            => 'Fake county',
                    'post_code'         => 'F4 K3E',
                    'max_daily_capacity' => $user->collectionPoint()->max_daily_capacity,
                ]]]);
    }

    public function testUpdateCollectionPointMaxDailyCapacity()
    {
        $user = factory(CollectionPointUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/collection-point', [
          'max_daily_capacity' => 130
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_point' => [
                    'id' => $user->collectionPoint()->id,
                    'name' => $user->collectionPoint()->name,
                    'address_line_1' => $user->collectionPoint()->address_line_1,
                    'address_line_2' => $user->collectionPoint()->address_line_2,
                    'city' => $user->collectionPoint()->city,
                    'county' => $user->collectionPoint()->county,
                    'post_code' => $user->collectionPoint()->post_code,
                    'max_daily_capacity' => 130,
                ]]]);
    }

    public function testUpdateCollectionPointAll()
    {
        $user = factory(CollectionPointUser::class)->create()->user;
        $response = $this->actingAs($user, "api")->postJson('/api/collection-point', [
            'name'              => 'Fake Collection point',
            'address_line_1'    => 'Fake house',
            'address_line_2'    => '123 Fake Street',
            'city'              => 'Fake city',
            'county'            => 'Fake county',
            'post_code'         => 'F4 K3E',
            "start_pick_up_time" => "2008-04-25 08:37:17",
            "end_pick_up_time" => "2008-04-25 08:37:17",
            "cut_off_point" => "2008-04-25 08:37:17",
            "set_quantity_per_person" => 2,
            "logo"  => "/ahmed-logo.jpg",
            "unique_url" => "/ahmed-bank",
            'max_daily_capacity'=> 1310
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'collection_point' => [
                    'id' => $user->collectionPoint()->id,
                    'name'              => 'Fake Collection point',
                    'address_line_1'    => 'Fake house',
                    'address_line_2'    => '123 Fake Street',
                    'city'              => 'Fake city',
                    'county'            => 'Fake county',
                    'post_code'         => 'F4 K3E',
                    "start_pick_up_time" => "2008-04-25 08:37:17",
                    "end_pick_up_time" => "2008-04-25 08:37:17",
                    "cut_off_point" => "2008-04-25 08:37:17",
                    "set_quantity_per_person" => 2,
                    "logo"  => "/ahmed-logo.jpg",
                    "unique_url" => "/ahmed-bank",
                    'max_daily_capacity'=> 1310
                ]]]);
    }

    public function testCollectionPointCanDeliveryToLocation()
    {
        factory(CollectionPoint::class)->create();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, "api")->postJson('/api/collection-points/1/can-deliver-to-location', [
            'postcode' => 'WC2R 1LA'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'can_deliver_to_location' => true ]]);
    }

    public function testCollectionPointCannotDeliveryToLocation()
    {
        factory(CollectionPoint::class)->create();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, "api")->postJson('/api/collection-points/1/can-deliver-to-location', [
            'postcode' => 'IG1 4DU'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data'   => [ 'can_deliver_to_location' => false ]]);
    }

    public function testGetCollectionPointNearMe()
    {
        factory(CollectionPoint::class)->create();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, "api")->postJson('/api/collection-points/near-me', [
            'postcode' => 'IG1 4DU'
        ]);

        $response
            ->assertStatus(200);
    }
}
