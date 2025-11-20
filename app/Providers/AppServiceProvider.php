<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // \Laravel\Passport\Passport::tokensExpireIn(Carbon::now()->addMinutes(2));
        // \Laravel\Passport\Passport::personalAccessTokensExpireIn(Carbon::now()->addMinutes(2));
        // \Laravel\Passport\Passport::refreshTokensExpireIn(Carbon::now()->addYears(1));
        // \Laravel\Passport\Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $previousUrl = url()->previous();
        view()->share('previousUrl', $previousUrl);
    }
}
