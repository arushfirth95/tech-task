<?php
namespace App\Providers;

use App\Library\Pricing\PricingCalculationService;
use Illuminate\Support\ServiceProvider;
use App\Library\Season\SeasonCalculationService;

class PricingCalculationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(PricingCalculationService::class, function () {
            return new PricingCalculationService($this->app->make(SeasonCalculationService::class));
        });
    }

    public function provides()
    {
        return [
            PricingCalculationService::class
        ];
    }
}
