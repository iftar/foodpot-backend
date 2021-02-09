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

    public function listNearLatLong($userLat, $userLong)
    {
        $radius = config('shareiftar.radius');
        $nearestPoints = [];
        $collectionPoints = CollectionPoint::all();

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
