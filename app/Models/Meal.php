<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $casts = [
        "quantity" => "integer",
        "collection_point_id" => "integer"

    ];
    public function collectionPoint() {
        return $this->belongsTo(CollectionPoint::class );
    }
    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
