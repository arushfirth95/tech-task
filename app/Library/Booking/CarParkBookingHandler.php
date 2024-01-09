<?php

namespace App\Library\Booking;

use App\Library\Pricing\PricingCalculationService;
use App\Library\Repositories\CarPark\CarParkBookingDay;
use App\Library\Repositories\CarPark\CarParkBooking;

class CarParkBookingHandler implements BookingHandler
{
    /**
     * @var int
     */
    protected $max_cars_per_day;
    /**
     * @var PricingCalculationService
     */
    protected $pricingCalculationService;

    /**
     * @param PricingCalculationService $pricingCalculationService
     */
    public function __construct(PricingCalculationService $pricingCalculationService)
    {
        $this->max_cars_per_day = (int)Config('app.max_cars_per_day');
        $this->pricingCalculationService = $pricingCalculationService;
    }

    /**
     * @param $date_obj_from
     * @param $date_obj_to
     * @return CarParkBooking
     * @throws \Exception
     */
    public function buildNewBooking($date_obj_from, $date_obj_to)
    {
        $booking = new CarParkBooking();

        $price = 0;
        $booking_days = [];
        if (!($date_obj_from->getTimestamp() === $date_obj_to->getTimestamp())) {
            $date_period = $this->getDatesInbetween($date_obj_from, $date_obj_to);
            foreach ($date_period as $date) {
                $booking_day = new CarParkBookingDay();
                $booking_day->setDate($date->format('Y-m-d'));
                $booking_days[] = $booking_day;
                $price += $this->pricingCalculationService->calculate($date);
            }
        } else {
            $booking_day = new CarParkBookingDay();
            $booking_day->setDate($date_obj_from->format('Y-m-d'));
            $booking_days[] = $booking_day;
            $price += $this->pricingCalculationService->calculate($date_obj_from);
        }

        $booking->setPrice($price);
        $booking->setBookingDays($booking_days);
        return $booking;
    }

    /**
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return \DatePeriod
     */
    public function getDatesInbetween(\DateTime $date_from_object, \DateTime $date_to_object)
    {
        //We add 1 day, so it takes into account the last parking day  (pickup)
        $date_to_object->modify('+1 day');
        return new \DatePeriod(
            $date_from_object,
            new \DateInterval('P1D'),
            $date_to_object
        );
    }

    /**
     * @param CarParkBookingDay[] $bookings
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return bool
     */
    public function isBookingAvailable(array $bookings, \DateTime $date_from_object, \DateTime $date_to_object)
    {
        while ($date_from_object->getTimestamp() <= $date_to_object->getTimestamp()) {
            // Count the number of bookings for the current date
            $bookings_for_date = array_filter($bookings, function ($booking) use ($date_from_object) {
                return $booking->getDate() === $date_from_object->format('Y-m-d');
            });

            // Check if the number of bookings for the date is less than the maximum allowed
            if (count($bookings_for_date) >= $this->max_cars_per_day) {
                return false;
            }
            $date_from_object->modify('+1 day');
        }
        return true;
    }

    /**
     * @param CarParkBookingDay[] $bookings
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return array
     */
    public function getBookingAvailabilityData(array $bookings, \DateTime $date_from_object, \DateTime $date_to_object)
    {
        $date_count = [];
        $days_between = $this->getDatesInbetween($date_from_object, $date_to_object);
        foreach ($days_between as $day) {
            if (!isset($date_count['spaces_available'][$day->format('Y-m-d')])) {
                $date_count['spaces_available'][$day->format('Y-m-d')] = $this->max_cars_per_day;
            }
        }

        foreach ($bookings as $booking) {
            $date_count['spaces_available'][$booking->getDate()]--;
        }
        return $date_count;
    }

    /**
     * @param \DateTime $date_from_object
     * @param \DateTime $date_to_object
     * @return int
     * @throws \Exception
     */
    public function getPriceForDates(\DateTime $date_from_object, \DateTime $date_to_object)
    {
        $price = 0;
        $days_between = $this->getDatesInbetween($date_from_object, $date_to_object);
        foreach ($days_between as $day_object) {
            $price += $this->pricingCalculationService->calculate($day_object);
        }
        return $price;
    }
}
