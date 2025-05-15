<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentBrandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            // Set logo untuk sidebar dan login page
            Filament::registerTheme([
                'logo' => asset('assets/logo.png'), // Path ke logo PNG
            ]);
        });

    }
}
