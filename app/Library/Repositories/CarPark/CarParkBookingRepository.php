<?php
namespace App\Library\Repositories\CarPark;

use PDO;

class CarParkBookingRepository implements CarParkBookingInterface
{
    protected $pdo;
    public function __construct(PDO $PDO)
    {
        $this->pdo = $PDO;
    }

    /**
     * @param $id
     * @return CarParkBooking|null
     */
    public function get($id){
        $query = 'Select * from booking where id=:id';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'id' => $id
        ]);
        $prepared->setFetchMode(PDO::FETCH_CLASS, CarParkBooking::class);
        return $prepared->fetch() ?? null;
    }

    public function insert(CarParkBooking $carParkBooking)
    {
        $query = 'INSERT INTO booking (`price`) VALUES (:price)';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'price' => $carParkBooking->getPrice()
        ]);

        $carParkBooking->setId($this->pdo->lastInsertId());
        return $carParkBooking;
    }

    public function update(CarParkBooking $carParkBooking)
    {
        // TODO: Implement update() method.
        return '';

    }

    public function delete($id)
    {
        $query = 'DELETE FROM booking where id = :id';
        $prepared = $this->pdo->prepare($query);
        $prepared->execute([
            'id' => $id
        ]);
    }
}
