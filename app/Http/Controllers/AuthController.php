<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class AuthController extends Controller
{
    public function verify(AuthService $authService, User $user, $hash)
    {
        if ( ! $authService->validateVerification($user, $hash)) {
            abort(404);
        }

        if ($user instanceof MustVerifyEmail && $user->hasVerifiedEmail()) {
            return redirect()->route('auth.verification.verified');
        }

        if ( ! $user->markEmailAsVerified()) {
            abort(404);
        }

        event(new Verified($user));

        $authService->login($user);

        return redirect()->route('auth.verification.verified');
    }

    public function verified()
    {
        return redirect()->away( config('app.frontend_url') . "/login?state=email_verified" );
    }
}
