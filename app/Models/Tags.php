<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    public function collection_points() {
        return $this->belongsToMany(CollectionPoint::class);
    }
}
