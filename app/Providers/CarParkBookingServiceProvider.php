<?php

namespace App\Providers;


use App\Library\Repositories\CarPark\CarParkBookingDayRepository;
use App\Library\Repositories\CarPark\CarParkBookingRepository;
use App\Library\Services\CarPark\CarParkBookingService;
use Illuminate\Support\ServiceProvider;

class CarParkBookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(CarParkBookingRepository::class, function () {
            return new CarParkBookingRepository($this->app->make('db')->connection('mysql')->getPdo());
        });

        $this->app->bind(CarParkBookingDayRepository::class, function () {
            return new CarParkBookingDayRepository($this->app->make('db')->connection('mysql')->getPdo());
        });
        $this->app->bind(CarParkBookingService::class, function () {
            return new CarParkBookingService($this->app->make(CarParkBookingRepository::class),$this->app->make(CarParkBookingDayRepository::class));
        });
    }

    public function provides()
    {
        return [
            CarParkBookingRepository::class,
            CarParkBookingDayRepository::class,
            CarParkBookingService::class,
        ];
    }
}
