<?php

namespace App\Library\Repositories\CarPark;

interface CarParkBookingInterface
{
    /**
     * @param $id
     * @return CarParkBooking|null
     */
    public function get($id);

    /**
     * @param CarParkBooking $carParkBooking
     * @return CarParkBooking
     */
    public function insert(CarParkBooking $carParkBooking);

    /**
     * @param CarParkBooking $carParkBooking
     * @return CarParkBooking
     */
    public function update(CarParkBooking $carParkBooking);

    /**
     * @param $id
     */
    public function delete($id);
}
