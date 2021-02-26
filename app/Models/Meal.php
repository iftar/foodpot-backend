<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    public function collectionPoint() {
        return $this->belongsTo(CollectionPoint::class );
    }

    public function orders() {
        return $this->belongsToMany(Order::class);
    }

}
