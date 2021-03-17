<?php

namespace App\Services\User;

use App\Models\CollectionPoint;

class CollectionPointService
{
    public function get($id)
    {
        return CollectionPoint::where('id', $id)->first();
    }

    public function list()
    {
        return CollectionPoint::paginate(15);
    }

    public function filterByTags($collection_points, $tags) {
        $filtered = [];

        foreach($collection_points as $collection_point) {
            foreach ($collection_point->tags as $tag) {
                if(in_array($tag->id, $tags)) {
                    array_push($filtered, $collection_point);
                }
            }
        }

        return $filtered;
    }

    public function listNearLatLong($collectionPoints, $userLat, $userLong)
    {
        $radius = config('shareiftar.radius');
        $nearestPoints = [];
//        $collectionPoints = CollectionPoint::all();

        foreach ($collectionPoints as $collectionPoint)
        {
            if(
                $this->getDistanceBetweenPoints(
                    $userLat,
                    $userLong,
                    $collectionPoint->lat,
                    $collectionPoint->lng
                ) <= $radius
            ) {
                $nearestPoints[] = $collectionPoint;
            }
        }

        return $nearestPoints;
    }

    public function canDeliverToLocation($collectionPointId, $userLat, $userLong)
    {
        $collectionPoint = $this->get($collectionPointId);
        $radius = $collectionPoint->delivery_radius;

        return $this->getDistanceBetweenPoints(
            $userLat,
            $userLong,
            $collectionPoint->lat,
            $collectionPoint->lng
        ) <= $radius;
    }

    private function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        return $miles;
    }
}
