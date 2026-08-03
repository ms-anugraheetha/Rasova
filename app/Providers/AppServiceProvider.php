<?php

namespace App\Providers;

use App\View\Composers\CartComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Every page using the storefront layout gets an accurate cart item
        // count automatically — no need for each controller to remember to
        // pass it individually (which was the bug: only HomeController did).
        View::composer('layouts.storefront', CartComposer::class);

        // Applies automatically everywhere Rules\Password::defaults() is used
        // (registration, password reset) — one place to define the policy
        // instead of duplicating rules across controllers.
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols();
        });
    }
}