<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPointTag extends Model
{
    protected $table = "collection_point_tag";
    protected $fillable = [
        "collection_point_id",
        "tag_id"
    ];
}
