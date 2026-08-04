<?php

namespace App\Providers;

use App\View\Composers\CartComposer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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

        // Rasova-branded copy for the two built-in auth emails, instead of
        // Laravel's generic "Hello! / Regards, Laravel" default wording.
        // The styling itself (burgundy button, cream background) comes from
        // the published theme at resources/views/vendor/mail/html/themes/default.css.
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify your email - Rasova')
                ->greeting('Welcome to Rasova!')
                ->line("Thanks for creating an account. We're excited to have you - just one more step before you can start shopping.")
                ->action('Verify Email Address', $url)
                ->line("If you didn't create an account with us, you can safely ignore this email.")
                ->salutation('Warmly, the Rasova team');
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset your password - Rasova')
                ->greeting('Forgot your password?')
                ->line('No problem - click below to choose a new one. This link will expire in 60 minutes.')
                ->action('Reset Password', $url)
                ->line("If you didn't request a password reset, no action is needed - your account is still secure.")
                ->salutation('Warmly, the Rasova team');
        });
    }
}