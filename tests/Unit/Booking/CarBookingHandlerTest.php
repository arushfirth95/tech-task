<?php

namespace Tests\Unit\Booking;


use App\Library\Booking\CarParkBookingHandler;
use App\Library\Repositories\CarPark\CarParkBookingDay;
use Illuminate\Container\Container;
use Tests\TestCase;


class CarBookingHandlerTest extends TestCase
{
    protected $carBookingHandler;

    public function setUp(): void
    {
        parent::setUp();
        $c = Container::getInstance();
        $this->carParkBookingHandler = $c->make(CarParkBookingHandler::class);
    }

    public function testBuildNewBooking()
    {
        // Testing building the model with multiple days
        $date_from_object_multi = \DateTime::createFromFormat('Y-m-d', '2024-01-01');
        $date_to_object_multi = \DateTime::createFromFormat('Y-m-d', '2024-01-05');

        $booking_multi = $this->carParkBookingHandler->buildNewBooking($date_from_object_multi, $date_to_object_multi);

        $this->assertEquals(100,$booking_multi->getPrice());
        $this->assertCount(5,$booking_multi->getBookingDays());

        //Testing building the model with 1 day
        $date_from_object_single = \DateTime::createFromFormat('Y-m-d', '2024-01-01');
        $date_to_object_single = \DateTime::createFromFormat('Y-m-d', '2024-01-01');

        $booking_single = $this->carParkBookingHandler->buildNewBooking($date_from_object_single, $date_to_object_single);

        $this->assertEquals(20,$booking_single->getPrice());
        $this->assertCount(1,$booking_single->getBookingDays());
    }

    public function testIsBookingAvailable()
    {
        $booking_days_available = [
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-02'),
            $this->setBookingDayModel('2024-01-02'),
            $this->setBookingDayModel('2024-01-04')];
        $booking_days_unavailable = [
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-02'),
            $this->setBookingDayModel('2024-01-02'),
            $this->setBookingDayModel('2024-01-04')];

        $this->assertTrue($this->carParkBookingHandler->isBookingAvailable($booking_days_available, \DateTime::createFromFormat('Y-m-d', '2024-01-01'), \DateTime::createFromFormat('Y-m-d', '2024-01-05')));
        $this->assertFalse($this->carParkBookingHandler->isBookingAvailable($booking_days_unavailable, \DateTime::createFromFormat('Y-m-d', '2024-01-01'), \DateTime::createFromFormat('Y-m-d', '2024-01-05')));
    }

    public function testGetBookingAvailabilityData()
    {
        $booking_days = [
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-01'),
            $this->setBookingDayModel('2024-01-02'),
            $this->setBookingDayModel('2024-01-02'),
            $this->setBookingDayModel('2024-01-04')];
        $date_from_object = \DateTime::createFromFormat('Y-m-d', '2024-01-01');
        $date_to_object = \DateTime::createFromFormat('Y-m-d', '2024-01-05');

        $date_count = $this->carParkBookingHandler->getBookingAvailabilityData($booking_days, $date_from_object, $date_to_object);

        $this->assertEquals(7, $date_count['spaces_available']['2024-01-01']);
        $this->assertEquals(8, $date_count['spaces_available']['2024-01-02']);
        $this->assertEquals(10, $date_count['spaces_available']['2024-01-03']);
        $this->assertEquals(9, $date_count['spaces_available']['2024-01-04']);
        $this->assertEquals(10, $date_count['spaces_available']['2024-01-05']);

        $this->assertCount(5, $date_count['spaces_available']);
    }

    public function testGetPriceForDates()
    {
        $date_from_object = \DateTime::createFromFormat('Y-m-d', '2024-01-01');
        $date_to_object = \DateTime::createFromFormat('Y-m-d', '2024-01-07');

        $price = $this->carParkBookingHandler->getPriceForDates($date_from_object, $date_to_object);

        $this->assertEquals(130, $price);
    }

    protected function setBookingDayModel($date)
    {
        $model = new CarParkBookingDay();
        $model->setDate($date);
        return $model;
    }
}
