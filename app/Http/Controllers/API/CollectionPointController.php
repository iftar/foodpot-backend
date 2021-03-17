<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Request;
use App\Models\CollectionPoint;
use App\Models\Meal;
use App\Services\User\CollectionPointService;
use App\Http\Requests\API\User\AuthenticatedRequest;
use App\Services\All\PostcodeService;

class CollectionPointController extends Controller
{
    public function index(AuthenticatedRequest $request, CollectionPointService $collectionPointService)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'collection_points' => $collectionPointService->list()
            ]
        ]);
    }

    public function indexNearMe(AuthenticatedRequest $request, CollectionPointService $collectionPointService, PostcodeService $postcodeService)
    {
        $postCode = $request->input('postcode');
        $food_type_filter = $request->input("food_type_filter") ?? [];
        $dietary_requirements_filter = $request->input("dietary_requirements_filter") ?? [];

        $tags = array_merge($food_type_filter, $dietary_requirements_filter);
        if(!empty($tags)) {
            $filteredCollectionPoints = $collectionPointService->filterByTags(CollectionPoint::all(), $tags);
        } else {
            $filteredCollectionPoints = CollectionPoint::all();
        }

        if( !$postCode)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Postcode paramaters are required.',
            ], 500);
        }

        $userLocation = $postcodeService->getLatLongForPostCode($postCode);

        if( isset($userLocation["error"]) )
        {
            return response()->json([
                'status' => 'error',
                'message' => $userLocation["error"],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'collection_points' => $collectionPointService->listNearLatLong(
                    $filteredCollectionPoints, $userLocation["latitude"], $userLocation["longitude"]
                )
            ]
        ]);
    }

    public function show($id, AuthenticatedRequest $request, CollectionPointService $collectionPointService)
    {
        if( !is_numeric($id) ) {
            $collection_point = CollectionPoint::where("slug", $id)->first();
        } else {
            $collection_point = $collectionPointService->get($id);
         }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'collection_point' => $collection_point
            ]
        ]);
    }

    public function canDeliverToLocation($collectionPointId, AuthenticatedRequest $request, CollectionPointService $collectionPointService, PostcodeService $postcodeService)
    {
        $postCode = $request->input('postcode');

        if( !$postCode)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Postcode paramaters are required.',
            ]);
        }

        $userLocation = $postcodeService->getLatLongForPostCode($postCode);

        if( isset($userLocation["error"]) )
        {
            return response()->json([
                'status' => 'error',
                'message' => $userLocation["error"],
            ]);
        }

        $canDeliverToLocation = $collectionPointService->canDeliverToLocation(
            $collectionPointId, $userLocation["latitude"], $userLocation["longitude"]
        );

        return response()->json([
            'status' => 'success',
            'data'   => [
                'can_deliver_to_location' => $canDeliverToLocation,
            ]
        ]);
    }

    public function getMeals($id) {
        $meals = Meal::with("tags")->where("collection_point_id", $id)->get();

        if($meals->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => "Collection point ID: {$id}  does have any meals or does not exist"
            ], 404);
        }
        $meals  = $meals->filter(function ($meal) {
            return $meal->quantity > 0;
        });

        return response()->json([
            'status' => 'success',
            'data'   => $meals->toArray()
        ]);
    }
}
