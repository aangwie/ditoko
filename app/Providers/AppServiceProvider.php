<?php

namespace App\Providers;

use App\Models\Setting;
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
        // Override Google OAuth config from database settings
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            try {
                $clientId = Setting::get('google_client_id');
                $clientSecret = Setting::get('google_client_secret');
                $redirectUri = Setting::get('google_redirect_uri');

                if ($clientId && $clientSecret && $redirectUri) {
                    config([
                        'services.google' => [
                            'client_id' => $clientId,
                            'client_secret' => $clientSecret,
                            'redirect' => $redirectUri,
                        ],
                    ]);
                }
            } catch (\Exception $e) {
                // table exists but schema mismatch or other issue — fallback to .env
            }
        }
    }
}