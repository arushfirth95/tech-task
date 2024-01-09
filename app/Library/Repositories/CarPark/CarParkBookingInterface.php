<?php

namespace App\Library\Repositories\CarPark;

interface CarParkBookingInterface
{
    public function insert(CarParkBooking $carParkBooking);

    public function update(CarParkBooking $carParkBooking);

    public function delete($id);
}
