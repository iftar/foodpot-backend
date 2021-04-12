<?php

namespace App\Services\CollectionPoint;

use App\Models\CollectionPoint;
use App\Events\CollectionPoint\Created;
use App\Events\CollectionPoint\Updated;
use Illuminate\Support\Facades\DB;

class CollectionPointService
{
    public function get()
    {
        $user = auth()->user();

        return $user->collectionPoints->first();
    }

    public function create($data)
    {
        $collectionPoint = CollectionPoint::create([
            "name"                   => $data["name"],
            "address_line_1"         => $data["address_line_1"],
            "address_line_2"         => $data["address_line_2"],
            "city"                   => $data["city"],
            "county"                 => $data["county"],
            "start_pick_up_time"     => $data['start_pick_up_time'] ,
            "end_pick_up_time"       => $data['end_pick_up_time'],
            "cut_off_point"          => $data['cut_off_point'],
            "post_code"              => $data["post_code"],
            "lat"                    => $data["lat"],
            "lng"                    => $data["lng"],
            "max_daily_capacity"     => $data["max_daily_capacity"],
            "slug"                   => $data["slug"] ?? null
        ]);

        event(new Created($collectionPoint));

        return $collectionPoint;
    }

    public function update(CollectionPoint $collectionPoint, $data)
    {
        $collectionPoint->update([
            'name'               => $data['name'] ?? $collectionPoint->name,
            'address_line_1'     => $data['address_line_1'] ?? $collectionPoint->address_line_1,
            'address_line_2'     => $data['address_line_2'] ?? $collectionPoint->address_line_2,
            'city'               => $data['city'] ?? $collectionPoint->city,
            'county'             => $data['county'] ?? $collectionPoint->county,
            "start_pick_up_time"       => $data['start_pick_up_time'] ?? $collectionPoint->start_pick_up_time,
            "end_pick_up_time"       => $data['end_pick_up_time'] ?? $collectionPoint->end_pick_up_time,
            "cut_off_point"      => $data['cut_off_point'] ?? $collectionPoint->cut_off_point,
            'post_code'          => $data['post_code'] ?? $collectionPoint->post_code,
            'max_daily_capacity' => $data['max_daily_capacity'] ?? $collectionPoint->max_daily_capacity,
        ]);

        event(new Updated($collectionPoint));

        return $collectionPoint->fresh();
    }

    public function getFillable($collection)
    {
        return $collection->only(
            with((new CollectionPoint())->getFillable())
        );
    }

    public function slugify($title)
    {
        $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));

        $count = DB::table("collection_points")
            ->where("slug", "LIKE", "%$slug%")
            ->count();

        return ($count > 0) ? ($slug . '-' . $count) : $slug;
    }
}
