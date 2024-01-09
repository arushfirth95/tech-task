<?php

namespace App\Library\Repositories\CarPark;

class CarParkBooking
{
    /**
     * @var mixed
     */
    protected $id;
    /**
     * @var mixed
     */
    protected $price;
    /**
     * @var CarParkBookingDay[]
     */

    protected $booking_days;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return CarParkBookingDay[]
     */
    public function getBookingDays()
    {
        return $this->booking_days;
    }

    /**
     * @param CarParkBookingDay $booking_day
     */
    public function setBookingDay($booking_day)
    {
        $this->booking_days[] = $booking_day;
    }

    /**
     * @param CarParkBookingDay[] $booking_days
     */
    public function setBookingDays($booking_days){
        $this->booking_days = $booking_days;
    }


}
