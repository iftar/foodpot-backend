<?php

namespace Tests\Feature;

use App\Models\CollectionPoint;
use App\Models\CollectionPointTag;
use App\Models\Meal;
use App\Models\MealTag;
use Tests\TestCase;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function collection_point_has_tags()
    {
        $tag = factory(Tag::class)->create();
        $collection_point = factory(CollectionPoint::class)->create();
        $meals = factory(Meal::class)->create([
            "collection_point_id" => $collection_point->id
        ]);

        factory(MealTag::class)->create([
            "meal_id" => $meals->id,
            "tag_id" => $tag->id
        ]);
        $this->assertTrue($collection_point->tags->pluck("id")->contains($tag->id));
    }

    /** @test  */
    public function dietary_requirements_endpoint_returns_tags()
    {
        $tags = factory(Tag::class, 12)->create([
            'type' => Tag::DIETARY_REQUIREMENT_TAG
        ]);
        $collection_point = factory(CollectionPoint::class)->create();


        $response = $this->get('api/tags/dietary-requirements');
        $response->assertJson([
            'status' => 'success',
            'data'   => $tags->toArray()
            ]);

    }
    /** @test  */
    public function food_type_endpoint_returns_tags()
    {
        $tags = factory(Tag::class, 12)->create([
            'type' => Tag::FOOD_TYPE_TAG
        ]);
        $collection_point = factory(CollectionPoint::class)->create();


        $response = $this->get('api/tags/food-type');
        $response->assertJson([
            'status' => 'success',
            'data'   => $tags->toArray()
        ]);

    }
}
