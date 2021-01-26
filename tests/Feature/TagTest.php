<?php

namespace Tests\Feature;

use App\Models\CollectionPoint;
use App\Models\CollectionPointTag;
use App\Models\User;
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
        $collection_point_tag = CollectionPointTag::create([
            "collection_point_id" => $collection_point->id,
            "tag_id" => $tag->id
        ]);

        $this->assertEquals($tag->id, $collection_point->tags->first()->id);
        $this->assertEquals($collection_point->id, $tag->collection_points->first()->id);
    }
}
