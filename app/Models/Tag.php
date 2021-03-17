<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    // Types of Tags
    const FOOD_TYPE_TAG = "FOOD_TYPE_TAG";
    const DIETARY_REQUIREMENT_TAG = "DIETARY_REQUIREMENT_TAG";

    public function collection_points() {
        return $this->belongsToMany(CollectionPoint::class);
    }
    public function meals() {
        return $this->belongsToMany(Meal::class);
    }
}
