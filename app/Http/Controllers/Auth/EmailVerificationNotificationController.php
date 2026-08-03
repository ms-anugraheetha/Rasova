<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmailVerificationNotificationController extends Controller
{
    protected const COOLDOWN_SECONDS = 60;

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false));
        }

        $cooldownKey = 'verification-resend:' . $request->user()->id;

        if (Cache::has($cooldownKey)) {
            return back()->with('status', 'verification-link-cooldown');
        }

        $request->user()->sendEmailVerificationNotification();
        Cache::put($cooldownKey, true, self::COOLDOWN_SECONDS);

        return back()->with('status', 'verification-link-sent');
    }
}