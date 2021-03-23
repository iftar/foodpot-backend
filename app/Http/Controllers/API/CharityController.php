<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\User\CharityService;
use App\Http\Requests\API\User\AuthenticatedRequest;
use Illuminate\Http\Request;

class CharityController extends Controller
{
    public function index(Request $request, CharityService $charityPointService)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'charities' => $charityPointService->list()
            ]
        ]);
    }
}
