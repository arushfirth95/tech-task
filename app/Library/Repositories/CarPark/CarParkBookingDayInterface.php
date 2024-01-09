<?php

namespace App\Library\Repositories\CarPark;

interface CarParkBookingDayInterface
{
    public function insert(CarParkBookingDay $bookingDay);

    public function update(CarParkBookingDay $bookingDay);

    public function delete($id);

    public function deleteByBookingId($booking_id);

    /**
     * @param $date_from
     * @param $date_to
     * @return CarParkBookingDay[]|[]
     */
    public function getAllBookingDayWithinDateRange($date_from, $date_to);

    /**
     * @param $date_from
     * @param $date_to
     * @param $booking_id
     * @return mixed
     */
    public function getBookingDayWithinDateRangeNotEqualBookingId($date_from, $date_to, $booking_id);

}
