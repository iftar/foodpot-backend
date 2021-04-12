<?php

namespace App\Http\Controllers\API;

use App\Models\CharityCollectionPoint;
use App\Models\CharityUser;
use App\Models\CollectionPoint;
use App\Models\User;
use App\Services\All\PostcodeService;
use App\Services\Charity\CharityService;
use App\Services\CollectionPoint\CollectionPointService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Response;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\LoginRequest;
use App\Http\Requests\API\User\RegisterRequest;
use App\Http\Requests\API\User\AuthenticatedRequest;
use App\Http\Requests\API\User\ResendVerifyEmailRequest;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $authService)
    {
        if ( ! $authService->attemptLogin($request->input('email'), $request->input('password'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid login details'
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = auth()->user();

        if ( $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User has not verified email'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $authService->createToken($user);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'user'  => $user,
                'token' => $token->accessToken
            ]
        ]);
    }

    public function register(RegisterRequest $request, UserService $userService)
    {
        $types  = [ "admin", "user", "charity", "collection-point"];

        if ($userService->exists($request->input('email'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User already exists with this email'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!in_array($request->input('type'), $types) ) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid Type provided'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = $userService->create($request->all());
        if($request->input("type") === "charity") {
            try {
                DB::beginTransaction();
                $charity = (new CharityService)->create([
                    'name' => $request->input('charity_name'),
                    'registration_number' => $request->input('registration_number'),
                    'contact_telephone' => $request->input('contact_telephone'),
                    'company_website' => $request->input('company_website'),
                ]);

                CharityUser::create([
                    'user_id' => $user->id,
                    'charity_id' => $charity->id
                ]);

                $slug = (new CollectionPointService())->slugify($request->input('charity_name'));

                $london_coords = [
                    'lat' => '51.509865',
                    'lng' => '-0.118092'
                ];
                if($request->input("post_code")) {
                    $userLocation = (new PostcodeService())->getLatLongForPostCode($request->input("post_code"));
                }

                $collection_point = CollectionPoint::create([
                    'name'               => $request->input('charity_name'),
                    'address_line_1'     => $request->input('address_line_1'),
                    'address_line_2'     => $request->input('address_line_2'),
                    'city'               =>  $request->input('city'),
                    'county'             => $request->input('county'),
                    'post_code'          => $request->input('post_code'),
                    'max_daily_capacity' => $request->input('max_daily_capacity') ?? 0,
                    'cut_off_point'      => $request->input('cut_off_point') ?? Carbon::parse('3pm')->toTimeString(),
                    'lat'                => $userLocation["lat"] ?? $london_coords['lat'],
                    'lng'                => $userLocation["lng"] ??  $london_coords['lng'],
                    'slug' => $slug
                ]);

                CharityCollectionPoint::create([
                    'collection_point_id' => $collection_point->id,
                    'charity_id' => $charity->id,
                ]);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'data'   => [
                        'user' => $user,
                        'collection_point' => $collection_point,
                        'charity' => $charity
                    ]
                ]);
            } catch (\Exception $e ){
                DB::rollBack();
                return response()->json(["message" => $e->getMessage()], 500);
            }
        }


        return response()->json([
            'status' => 'success',
            'data'   => [
                'user' => $user
            ]
        ]);
    }

    public function logout(AuthenticatedRequest $request, AuthService $authService)
    {
        /** @var User $user */
        $user = auth()->user();

        $authService->revokeAllTokens($user);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function resendVerifyEmail(ResendVerifyEmailRequest $request, UserService $userService)
    {
        /** @var User $user */
        $user = $userService->exists($request->input('email'));

        if ($user && $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'message' => 'Verification email sent if user exists'
            ]
        ]);
    }
}
