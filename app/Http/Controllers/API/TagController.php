<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    public function getDietaryRequirementsTags() {
        $tags = Tag::where("type", Tag::DIETARY_REQUIREMENT_TAG)->get();

        return response()->json([
            'status' => 'success',
            'data'   =>  $tags
        ]);
    }

    public function getFoodTypeTags() {
        $tags = Tag::where("type", Tag::FOOD_TYPE_TAG)->get();

        return response()->json([
            'status' => 'success',
            'data'   =>  $tags
        ]);
    }
}
