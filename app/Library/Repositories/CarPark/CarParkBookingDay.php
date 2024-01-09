<?php

namespace App\Library\Repositories\CarPark;

class CarParkBookingDay
{
    protected $id;
    protected $date;
    protected $booking_id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getBookingId()
    {
        return $this->booking_id;
    }

    /**
     * @param mixed $booking_id
     */
    public function setBookingId($booking_id)
    {
        $this->booking_id = $booking_id;
    }


}
