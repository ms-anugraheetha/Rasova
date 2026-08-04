<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $alreadyVerified = $request->user()->hasVerifiedEmail();

        if (! $alreadyVerified && $request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Log out afterward so the user lands on a fresh success page,
        // per spec — verifying shouldn't silently drop them into the site.
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('verification.success');
    }
}