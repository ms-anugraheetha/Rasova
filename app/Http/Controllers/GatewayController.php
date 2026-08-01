<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GatewayController extends Controller
{
    /**
     * Guest mode lasts 60 days before the gateway reappears — long enough
     * that a returning shopper isn't nagged every visit, short enough that
     * it isn't effectively permanent.
     */
    protected const GUEST_MODE_MINUTES = 60 * 24 * 60;

    public function show(Request $request)
    {
        // Already made a choice — skip straight to the homepage.
        if ($request->user() || $request->cookie('rasova_guest_mode')) {
            return redirect()->route('home');
        }

        return view('gateway');
    }

    public function continueAsGuest()
    {
        return redirect()->route('home')
            ->cookie('rasova_guest_mode', '1', self::GUEST_MODE_MINUTES);
    }
}