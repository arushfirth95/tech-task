<?php

namespace App\Library\Booking;

use App\Library\Repositories\CarPark\CarParkBooking;

interface BookingHandler
{
    /**
     * @param $date_obj_from
     * @param $date_obj_to
     * @return Object
     */
    public function buildNewBooking($date_obj_from, $date_obj_to);

    /**
     * @param Object[] $bookings
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return mixed
     */
    public function isBookingAvailable(array $bookings, \DateTime $date_from_object, \DateTime $date_to_object);

    /**
     * @param Object[] $bookings
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return mixed
     */
    public function getBookingAvailabilityData(array $bookings, \DateTime $date_from_object, \DateTime $date_to_object);

    /**
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return mixed
     */
    public function getPriceForDates(\DateTime $date_from_object, \DateTime $date_to_object);
}
