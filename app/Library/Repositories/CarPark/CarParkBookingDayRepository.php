<?php

namespace App\Library\Repositories\CarPark;

use PDO;

class CarParkBookingDayRepository implements CarParkBookingDayInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @param PDO $PDO
     */
    public function __construct(PDO $PDO)
    {
        $this->pdo = $PDO;
    }

    /**
     * @param $booking_id
     * @return CarParkBookingDay[]|[]
     */
    public function getAllByBookingId($booking_id){
        $query = 'Select * from booking_day where booking_id=:booking_id';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'booking_id' => $booking_id
        ]);
        return $prepared->fetchAll(\PDO::FETCH_CLASS, CarParkBookingDay::class) ?? [];
    }

    /**
     * @param $date_from
     * @param $date_to
     * @return CarParkBookingDay[]|[]
     */
    public function getAllBookingDayWithinDateRange($date_from,$date_to){
        $query = 'Select * from booking_day where `date` >= :from and `date` <= :to';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'from' => $date_from,
            'to' => $date_to
        ]);
        return $prepared->fetchAll(\PDO::FETCH_CLASS, CarParkBookingDay::class) ?? [];
    }

    /**
     * @param $date_from
     * @param $date_to
     * @param $booking_id
     * @return CarParkBookingDay[]|[]
     */
    public function getBookingDayWithinDateRangeNotEqualBookingId($date_from,$date_to,$booking_id){
        $query = 'Select * from booking_day where `date` >= :from and `date` <= :to and booking_id != :booking_id';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'from' => $date_from,
            'to' => $date_to,
            'booking_id' => $booking_id
        ]);
        return $prepared->fetchAll(\PDO::FETCH_CLASS, CarParkBookingDay::class) ?? [];
    }

    /**
     * @param CarParkBookingDay $bookingDay
     * @return CarParkBookingDay
     */
    public function insert(CarParkBookingDay $bookingDay)
    {
        $query = 'INSERT INTO booking_day (`date`,`booking_id`) VALUES (:date, :booking_id)';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'date' => $bookingDay->getDate(),
            'booking_id' => $bookingDay->getBookingId()
        ]);
        $bookingDay->setId($this->pdo->lastInsertId());
        return $bookingDay;
    }

    /**
     * @param CarParkBookingDay $bookingDay
     * @return CarParkBookingDay
     */
    public function update(CarParkBookingDay $bookingDay)
    {
        // TODO: Implement update() method.
        return $bookingDay;
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $query = 'DELETE FROM booking_day where id = :id';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'id' => $id
        ]);
    }

    /**
     * @param $booking_id
     */
    public function deleteByBookingId($booking_id)
    {
        $query = 'DELETE FROM booking_day where booking_id = :booking_id';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'booking_id' => $booking_id
        ]);
    }
}
