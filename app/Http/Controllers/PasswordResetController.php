<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\UserService;
use App\Services\PasswordResetService;
use App\Http\Requests\API\User\UpdatePasswordRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class PasswordResetController extends Controller
{
    public function index(UserService $userService, $token)
    {
        if ( ! $userService->exists(request()->get('email'))) {
            abort(404);
        }

        return view('auth.password.reset')->with(
            ['token' => $token, 'email' => request()->get('email')]
        );
    }

    public function reset(UpdatePasswordRequest $request, UserService $userService, AuthService $authService, PasswordResetService $passwordResetService)
    {
        $user = $userService->exists(request()->input('email'));

        if ( ! $user) {
            abort(404);
        }

        if ( ! $passwordResetService->reset($request)) {
            abort(404, 'There was an error resetting your password');
        }

        if ($user && $user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $authService->revokeAllTokens($user);

        return redirect()->away( config('app.frontend_url') . "/login?state=password_reset_successfully" );
    }
}
