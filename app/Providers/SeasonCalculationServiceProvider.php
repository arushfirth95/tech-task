<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Season\SeasonCalculationService;

class SeasonCalculationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(SeasonCalculationService::class, function () {
            return new SeasonCalculationService();
        });
    }

    public function provides()
    {
        return [
            SeasonCalculationService::class
        ];
    }
}
