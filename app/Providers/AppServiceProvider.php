<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

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
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $frontendUrl = str_replace(env('BACKEND_API_URL'), env('FRONTEND_URL'), $url);
            // $frontendUrl = env('FRONTEND_URL').'/verify-email?verify_url=' . urlencode($url);
            return (new MailMessage)
                ->subject('Email de vérification ')
                ->line('Merci de cliquez sur le bouton pour valider la création de votre compte.')
                ->action('Vérifier mon adresse', $frontendUrl);
        });
    }
}
