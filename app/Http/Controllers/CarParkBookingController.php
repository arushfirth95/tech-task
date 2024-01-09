<?php

namespace App\Http\Controllers;

use App\Library\Booking\CarParkBookingHandler;
use App\Library\Services\CarPark\CarParkBookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarParkBookingController extends Controller
{
    /**
     * @var CarParkBookingService
     */
    protected $carParkBookingService;
    /**
     * @var CarParkBookingHandler
     */
    protected $carParkBookingHandler;

    /**
     * @param CarParkBookingService $carParkBookingService
     * @param CarParkBookingHandler $carParkBookingHandler
     */
    public function __construct(CarParkBookingService $carParkBookingService, CarParkBookingHandler $carParkBookingHandler)
    {

        $this->carParkBookingService = $carParkBookingService;
        $this->carParkBookingHandler = $carParkBookingHandler;
    }

    /**
     * This endpoint will take your date range and return you available spaces within that date range by date
     *
     * @param Request $request
     *          The HTTP request containing date_from and date_to
     * @return JsonResponse
     *          Will return a JSON response with the spaces available
     */
    public function checkAvailability(Request $request)
    {
        $date_from_object = \DateTime::createFromFormat('Y-m-d', $request->date_from);
        $date_to_object = \DateTime::createFromFormat('Y-m-d', $request->date_to);

        $bookings = $this->carParkBookingService->getAllBookingDayWithinDateRange($date_from_object->format('Y-m-d'), $date_to_object->format('Y-m-d'));
        $date_count = $this->carParkBookingHandler->getBookingAvailabilityData($bookings, $date_from_object, $date_to_object);

        //Check if booking is available and return the spaces available regardless of availability
        if (!$this->carParkBookingHandler->isBookingAvailable($bookings, $date_from_object, $date_to_object)) {
            return response()->json(['message' => 'Parking range is not available', 'available_spaces' => $date_count['spaces_available']]);
        }
        return response()->json(['message' => 'Parking range is available', 'available_spaces' => $date_count['spaces_available']]);
    }

    /**
     * This endpoint will take your date range and return you the price to park for said date range
     *
     * @param Request $request
     *          The HTTP request containing date_from and date_to
     * @return JsonResponse
     *          Will return a JSON response with the price
     */
    public function checkPrice(Request $request)
    {
        $date_from_object = \DateTime::createFromFormat('Y-m-d', $request->date_from);
        $date_to_object = \DateTime::createFromFormat('Y-m-d', $request->date_to);

        try {
            $price = $this->carParkBookingHandler->getPriceForDates($date_from_object, $date_to_object);

        } catch (\Exception $exception) {
            Log::error('CarParkBookingController - checkPrice - Internal Server Error',
                [
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'file_line' => $exception->getLine(),
                ]
            );
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
        return response()->json(['price' => $price]);
    }

    /**
     * This endpoint will take your date range and create a new parking booking
     *
     * @param Request $request
     *          The HTTP request containing date_from and date_to
     * @return JsonResponse
     *          Will return a JSON response with the success message of creating the booking and the booking reference
     * @throws \Exception
     */
    public function createBooking(Request $request)
    {
        $date_from_object = new \DateTime($request->date_from);
        $date_to_object = new \DateTime($request->date_to);

        try {
            $bookings = $this->carParkBookingService->getAllBookingDayWithinDateRange($date_from_object->format('Y-m-d'), $date_to_object->format('Y-m-d'));
            if (!$this->carParkBookingHandler->isBookingAvailable($bookings, new \DateTime($request->date_from), new \DateTime($request->date_to))) {
                return response()->json(['message' => 'Parking range is not available']); //check http codes
            }

            $booking = $this->carParkBookingHandler->buildNewBooking($date_from_object, $date_to_object);
            $this->carParkBookingService->insert($booking);
        } catch (\Exception $exception) {
            Log::error('CarParkBookingController - createBooking - Internal Server Error',
                [
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'error_message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'file_line' => $exception->getLine(),
                ]
            );
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
        return response()->json(['message' => 'Booking created successfully', 'booking_reference' => $booking->getId()], 201);
    }

    /**
     * This endpoint will take the booking_id and cancel the booking (delete)
     *
     * @param Request $request
     *          The HTTP request containing booking_id
     * @return JsonResponse
     *          Will return a JSON response with the success message of canceling the booking
     * @throws \Exception
     */
    public function cancelBooking(Request $request)
    {
        $booking_id = $request->booking_id;
        try {
            $this->carParkBookingService->delete($booking_id);
        } catch (\Exception $exception) {
            Log::error('CarParkBookingController - cancelBooking - Internal Server Error',
                [
                    'booking_id' => $booking_id,
                    'errorMessage' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'fileLine' => $exception->getLine(),
                ]
            );
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
        return response()->json(['message' => 'Booking canceled successfully']);
    }

    /**
     * This endpoint will take a new date range and booking_id and amend the existing booking with the new dates
     *
     * @param Request $request
     *          The HTTP request containing date_from, date_to and booking_id
     * @return JsonResponse
     *          Will return a JSON response with the success message of amending the booking
     * @throws \Exception
     */
    public function amendBooking(Request $request)
    {
        $date_from_object = new \DateTime($request->date_from);
        $date_to_object = new \DateTime($request->date_to);
        $booking_id = $request->booking_id;

        $booking = $this->carParkBookingService->getFullBookingData($booking_id);
        if (!$booking) {
            return response()->json(['message' => 'Booking doesn\'t exist'], 404);
        }
        try {
            $bookings = $this->carParkBookingService->getBookingDayWithinDateRangeNotEqualBookingId($date_from_object->format('Y-m-d'), $date_to_object->format('Y-m-d'), $booking_id);
            if (!$this->carParkBookingHandler->isBookingAvailable($bookings, new \DateTime($request->date_from), new \DateTime($request->date_to))) {
                return response()->json(['message' => 'Parking range is not available']);
            }

            $updated_booking = $this->carParkBookingHandler->buildNewBooking($date_from_object, $date_to_object);
            $updated_booking->setId($booking->getId());
            $this->carParkBookingService->updateFullBooking($booking);

        } catch (\Exception $exception) {
            Log::error('CarParkBookingController - amendBooking - Internal Server Error',
                [
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'booking_id' => $booking_id,
                    'errorMessage' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'fileLine' => $exception->getLine(),
                ]
            );
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
        return response()->json(['message' => 'Booking amended successfully']);
    }
}
