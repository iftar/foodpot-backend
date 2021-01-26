<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    // Types of Tags
    const MEAL_TYPE = "MEAL_TYPE";
    const DIETARY_REQUIREMENT = "DIETARY_REQUIREMENT";

    public function collection_points() {
        return $this->belongsToMany(CollectionPoint::class);
    }
}
