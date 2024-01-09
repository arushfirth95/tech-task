<?php

namespace App\Library\Services\CarPark;

use App\Library\Repositories\CarPark\CarParkBooking;
use App\Library\Repositories\CarPark\CarParkBookingDayRepository;
use App\Library\Repositories\CarPark\CarParkBookingInterface;
use App\Library\Repositories\CarPark\CarParkBookingRepository;

class CarParkBookingService implements CarParkBookingInterface
{
    protected $carParkBookingRepository;
    protected $carParkBookingDayRepository;

    public function __construct(CarParkBookingRepository $carParkBookingRepository, CarParkBookingDayRepository $bookingDayRepository)
    {
        $this->carParkBookingRepository = $carParkBookingRepository;
        $this->carParkBookingDayRepository = $bookingDayRepository;
    }

    public function insert(CarParkBooking $carParkBooking)
    {
        $this->carParkBookingRepository->insert($carParkBooking);

        foreach ($carParkBooking->getBookingDays() as $bookingDay) {
            $bookingDay->setBookingId($carParkBooking->getId());
            $this->carParkBookingDayRepository->insert($bookingDay);
        }
    }

    /**
     * @param CarParkBooking $car_park_booking_model
     * @return CarParkBooking
     */
    public function updateFullBooking($car_park_booking_model)
    {
        $this->deleteDayByBookingId($car_park_booking_model->getId());

        $this->update($car_park_booking_model);

        foreach ($car_park_booking_model->getBookingDays() as $booking_day) {
            $this->carParkBookingDayRepository->insert($booking_day);

        }
        return $car_park_booking_model;
    }

    public function update(CarParkBooking $carParkBooking)
    {
        return $this->carParkBookingRepository->update($carParkBooking);
    }

    public function delete($id)
    {
        $this->carParkBookingDayRepository->deleteByBookingId($id);
        $this->carParkBookingRepository->delete($id);
    }

    public function deleteDayByBookingId($booking_id)
    {
        $this->carParkBookingDayRepository->deleteByBookingId($booking_id);
    }

    /**
     * @param $booking_id
     * @return CarParkBooking|null
     */
    public function getFullBookingData($booking_id)
    {
        $booking = $this->carParkBookingRepository->get($booking_id);
        if ($booking instanceof CarParkBooking) {
            $booking_days = $this->carParkBookingDayRepository->getAllByBookingId($booking_id);
            $booking->setBookingDays($booking_days);
        }
        return $booking;
    }

    public function getAllBookingDayWithinDateRange($date_from, $date_to)
    {
        return $this->carParkBookingDayRepository->getAllBookingDayWithinDateRange($date_from, $date_to);
    }

    public function getBookingDayWithinDateRangeNotEqualBookingId($date_from, $date_to, $booking_id)
    {
        return $this->carParkBookingDayRepository->getBookingDayWithinDateRangeNotEqualBookingId($date_from, $date_to, $booking_id);
    }
}
